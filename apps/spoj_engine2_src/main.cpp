#include <stdio.h>
#include <iostream>

#include <unistd.h>

#include <QtCore/QCoreApplication>
#include <QtSql>
#include <QString>
#include <QProcess>
#include <QSqlQuery>
#include <QDebug>
#include <QSqlError>


#define DBTYPE "QMYSQL"
#define HOST "localhost"
#define GATE 3306
#define DATABASE "projekt5"
#define USER "projekt5"
#define PASS "projekt5"
//LIMITY PROB POLOCZENI Z BAZA
#define DBLIMIT 2


using namespace std;

int main (int argc, char *argv[])
{
    if (argc < 2)
    {
        cout << "Error. Solution id was not specified.\n";
        return 1;
    }
    int solCounter =1;

    QSqlDatabase database = QSqlDatabase::addDatabase(DBTYPE);
    database.setHostName(HOST);
    database.setPort(GATE);
    database.setDatabaseName(DATABASE);
    database.setUserName(USER);
    database.setPassword(PASS);

    while (solCounter < argc)
    {
        if (database.isOpen())
            database.close();

        int solutionId = atoi(argv[solCounter]);
        solCounter++;

        database.open();
        int tryCount = 0;
        while (!database.isOpen() && tryCount++ < DBLIMIT)
        {
            cout << "Database connection error. Attempting to reconnect in 5 seconds.\n";
            sleep(5);
            cout <<  "Reconnecting attempt... " << tryCount << "/" << DBLIMIT << "\n";
            database.open();
        }
        if (!database.isOpen())
        {
            cout << "Database connection error.";
            return 2;
        }

        QSqlQuery solutionUpdateQuery(database);

        //compiling

        QSqlQuery solutionQuery(database);
        QString solutionQueryString = QString("SELECT lang_id, solution, task_id, error FROM solutions WHERE id = %1 ;").arg(solutionId);
        solutionQuery.exec(solutionQueryString);
        solutionQuery.next();
        if (solutionQuery.value(3).toString() != QString("WAIT_FOR_RUN"))
            continue;

        QSqlQuery taskQuery(database);
        QString taskQueryString = QString("SELECT runTime, points FROM taskList WHERE id = %1 ;").arg(solutionQuery.value(2).toInt());
        taskQuery.exec(taskQueryString);
        taskQuery.next();

        QSqlQuery languageQuery(database);
        QString languageQueryString = QString("SELECT compile_string, file_format, script_language FROM languages WHERE id = %1 ;").arg(solutionQuery.value(0).toInt());
        languageQuery.exec(languageQueryString);
        languageQuery.next();

        QString ext = languageQuery.value(1).toString();
        ext.truncate(ext.indexOf(QChar('\n')));
        QString solutionSrcFile = QString("../solutions/sol_%1%2").arg(solutionId).arg(ext);
        QString solutionExeFile = QString("../solutions/sol_%1").arg(solutionId);
        QFile solution(solutionSrcFile);
        solution.open(QIODevice::WriteOnly);
        solution.write(solutionQuery.value(1).toByteArray());
        solution.close();


        if (!languageQuery.value(2).toBool())
        {
            QString compilerProcessString = QString("sh -c \"");
            compilerProcessString.append(QString(languageQuery.value(0).toByteArray()).arg(solutionSrcFile).arg(solutionExeFile));
            compilerProcessString.append(QString("\""));
            bool errorFlag = false;
            QProcess *compilerProcess;
            compilerProcess = new QProcess;
            compilerProcess->start(compilerProcessString);
            if (!compilerProcess->waitForStarted())
                errorFlag = true;
            if (!compilerProcess->waitForFinished())
                errorFlag = true;
            if (compilerProcess->state() != QProcess::NotRunning)
                errorFlag = true;

            if (errorFlag)
            {
                compilerProcess->kill();
                QString solutionUpdateQueryString = QString("UPDATE solutions SET error = 'UNDEFINED_ERROR WHERE' id = %1 ;").arg(solutionId);
                solutionUpdateQuery.exec(solutionUpdateQueryString);
                QFile::remove(solutionSrcFile);
                QFile::remove(solutionExeFile);
                continue;
            }

            if (compilerProcess->exitCode())
            {
                QString solutionUpdateQueryString = QString("UPDATE solutions SET error = 'COMPILATION_ERROR', error_str = \"%1\" WHERE id = %2 ;")
                        .arg(compilerProcess->readAllStandardError().replace('"', "\\\"").replace('\'', "\\\'").replace('\\', "\\\\")
                        , QString::number(solutionId));
                solutionUpdateQuery.exec(solutionUpdateQueryString);
                QFile::remove(solutionSrcFile);
                QFile::remove(solutionExeFile);
                continue;
            }
            compilerProcess->close();
            delete compilerProcess;
            QFile::remove(solutionSrcFile);
        }
        else
            solutionExeFile = solutionSrcFile;

        QProcess *scriptProcess;
        scriptProcess = new QProcess;
        scriptProcess->execute(QString("chmod 755 %1").arg(solutionExeFile));
        scriptProcess->waitForFinished();
        scriptProcess->close();
        delete scriptProcess;

        //running

        QSqlQuery taskIOQuery(database);
        QString taskIOQueryString = QString("SELECT arguments, input_string, output_string, return_value FROM taskIO WHERE task_id = %1 ;").arg(solutionQuery.value(2).toInt());
        taskIOQuery.exec(taskIOQueryString);
        int runTime = taskQuery.value(0).toInt();
        int parts=taskIOQuery.size(), runtimeErrCounter=0, ii=0;

        QString outputFile = QString("../solutions/output_%1.log").arg(solutionId);
        QFile solutionOutput(outputFile);
        bool hardError = false;
        QString errStr = "";


        while (taskIOQuery.next() && !hardError)
        {
            ii++;
            QString arguments = "";
            if (!taskIOQuery.value(0).isValid())
            {
                arguments = taskIOQuery.value(0).toString();
            }

            QProcess *solutionProcess;
            solutionProcess = new QProcess;
            solutionProcess->setStandardOutputFile(outputFile, QIODevice::WriteOnly);
            solutionProcess->start(solutionExeFile, QStringList(arguments));
            if (!solutionProcess->waitForStarted())
            {
                solutionProcess->kill();
                solutionProcess->close();
                delete solutionProcess;
                hardError = true;
                continue;
            }
            solutionProcess->write(taskIOQuery.value(1).toByteArray());
            solutionProcess->closeWriteChannel();
            solutionProcess->waitForFinished(runTime);

            if (solutionProcess->state() != QProcess::NotRunning)
            {
                errStr.append(QString("%1. Przekroczenie czasu\n").arg(ii));
                runtimeErrCounter++;
                solutionProcess->kill();
                solutionProcess->close();
                delete solutionProcess;
                parts--;
                continue;
            }

            solutionProcess->close();
            solutionOutput.open(QIODevice::ReadOnly);
            QByteArray output = solutionOutput.readAll();
            solutionOutput.close();

            if (!(output == taskIOQuery.value(2).toByteArray()))
            {
                errStr.append(QString("%1. Błędne wyniki\n").arg(ii));
                parts--;
                delete solutionProcess;
                continue;
            }

            if (solutionProcess->exitCode() != taskIOQuery.value(3).toInt())
            {
                errStr.append(QString("%1. Błędna wartość return\n").arg(ii));
                parts--;
                delete solutionProcess;
                continue;
            }
            errStr.append(QString("%1. Wynik poprawny\n").arg(ii));
            delete solutionProcess;
        }

        if (hardError)
        {
            QString solutionUpdateQueryString = QString("UPDATE solutions SET points = 0, error = 'RUNTIME_ERROR', error_str = 'Nie można włączyć programu.\n' WHERE id = %1 ;").arg(solutionId);
            solutionUpdateQuery.exec(solutionUpdateQueryString.toUtf8().data());
            QFile::remove(solutionExeFile);
            QFile::remove(outputFile);
            continue;
        }

        QString solutionUpdateQueryString="";
        if (parts == taskIOQuery.size())
            solutionUpdateQueryString = QString("UPDATE solutions SET error = 'NO_ERROR', points = %1 WHERE id = %2 ;").arg(QString::number(taskQuery.value(1).toDouble())).arg(solutionId);
        else
            solutionUpdateQueryString = QString("UPDATE solutions SET error = 'MIXED_ERROR', error_str = '%1', points = %2 WHERE id = %3 ;").arg(errStr, QString::number(taskQuery.value(1).toDouble() * parts / taskIOQuery.size(),'f',2)).arg(solutionId);
        solutionUpdateQuery.exec(solutionUpdateQueryString.toUtf8().data());
        QFile::remove(solutionExeFile);
        QFile::remove(outputFile);
    }

    //QCoreApplication a(argc, argv);
    return 0;
}
