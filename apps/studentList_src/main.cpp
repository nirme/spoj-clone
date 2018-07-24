#define _CRT_SECURE_NO_WARNINGS


#include "BasicExcel.hpp"
#include "ExcelFormat.h"
#include <stdio.h>
#include <unistd.h>

#include <QtCore/QCoreApplication>
#include <QtSql>
#include <QString>
#include <QSqlQuery>
//#include <QDebug>
#include <QSqlError>
#include <QCryptographicHash>


#define DBTYPE "QMYSQL"
#define HOST "localhost"
#define GATE 3306
#define DATABASE "projekt5"
#define USER "projekt5"
#define PASS "projekt5"
//LIMITY PROB POLOCZENI Z BAZA I OTWARC PLIKU XLS
#define DBLIMIT 10


using namespace std;
using namespace YExcel;

int getCellVal(QString &mystring, BasicExcelWorksheet* sheet, int x, int y)
{
        mystring.clear();
	int fl = sheet->Cell(x,y)->Type();
	if (fl == BasicExcelCell::STRING)
	{
                mystring = sheet->Cell(x,y)->GetString();
		return 1;
	}
	else if (fl == BasicExcelCell::WSTRING)
	{
		QString temp = QString::fromWCharArray(sheet->Cell(x,y)->GetWString());
                mystring = temp;
		return 1;
	}
	else
		return 0;
}

int main (int argc, char *argv[])
{
	srand (int(time(NULL)));
        if (argc != 2)
        {
		if (argc < 2)
			cout << "Error. Input file was not specified.\n";
		if (argc > 2)
			cout << "Error. Too many arguments.\n";
                return 1;
        }

	QSqlDatabase database = QSqlDatabase::addDatabase(DBTYPE);
	database.setHostName(HOST);
	database.setPort(GATE);
	database.setDatabaseName(DATABASE);
	database.setUserName(USER);
	database.setPassword(PASS);
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

        FILE *phpFile, *myFile;
        char tmpName[500]="0";
        strcpy(tmpName, argv[1]);
        char* here = strrchr(tmpName, '/');
        if (here != NULL)
        {
            here+=1;
            strcpy(here, "tmp_file.xls\0");
        }
        else
            strcpy(tmpName, "tmp_file.xls\0");

        phpFile = fopen(argv[1], "rb");
        myFile = fopen(tmpName, "wb");
        if (phpFile == NULL)
        {
            cout << "File open error: " << argv[1] << endl;
            return 3;
        }
        if (myFile == NULL)
        {
            cout << "Temp file create error" << endl;
            return 4;
        }
        char buff[1024] = "\0";
        int size = 0;
        while (!feof(phpFile) && !ferror(phpFile))
        {
            size = fread(buff, 1, 1024, phpFile);
            fwrite(buff, 1, size, myFile);
        }
        if (ferror(phpFile))
        {
            cout << "File read error." << endl;
            return 5;
        }
        fclose(phpFile);
        fclose(myFile);

        BasicExcel xls;
        if (!xls.Load(tmpName))
        {
            cout << "Specified file is not excel file. " << endl;
            return 6;
        }
	BasicExcelWorksheet* sheet = xls.GetWorksheet(0);
	int rows = sheet->GetTotalRows(), test = 0, c0=0, c1=0, c2=0, c3=0;
	// XLS TYPES: 0-UNDEFINED, 1-INTEGER, 2-DOUBLE, 3-STRING, 4-WSTRING, 5-FORMULA
	while (test < rows)
	{
		c0=sheet->Cell(test, 0)->Type();
		c1=sheet->Cell(test, 1)->Type();
		c2=sheet->Cell(test, 2)->Type();
		c3=sheet->Cell(test, 3)->Type();
                if ((c0 != 3 && c0 != 4) || (c1 != 3 && c1 != 4) || (c2 != 1 && c2 != 2) || (c3 != 3))
		{
			cout << "Error. Wrong table format.\nc0=" << c0 << "; c1=" << c1 << "; c2=" << c2 << "; c3=" << c3 << ";\n";
                        return 7;
		}
		test++;
	}

        QSqlQuery insertQuery(database), testQuery(database);
        QString templ = "insert into users (name, surname, pass, mail, indeks) values (\"%1\", \"%2\", \"%3\", \"%4\", %5);";
        QString testTemplIndex = "SELECT id FROM users WHERE %1 = %2";
        QString testTemplMail = "SELECT id FROM users WHERE %1 = \"%2\"";
        QString statement, name, surname, mail, index, pass;
        QRegExp mailPattern("^[a-zA-Z0-9_\\.\\-]+@[a-zA-Z0-9\\-]+\\.[a-zA-Z0-9\\-\\.]+$");
        char passwd[9];

        int i=0, insert_test = 0, pasch=0;
	while (i < rows)
	{
                getCellVal(name, sheet, i, 0);
                getCellVal(surname, sheet, i, 1);
                getCellVal(mail, sheet, i, 3);
                index.clear();
                index = QString::number(sheet->Cell(i,2)->GetInteger());
                strcpy(passwd, "AAAAAAAA");
                for (pasch=0; pasch < 8; pasch++)
                {
                    passwd[pasch]+= (rand()%26);
                    if (rand()%2)
                        passwd[pasch]+= 32;
                }
                passwd[pasch]='\0';
                pass = passwd;

                cout << index.toStdString() << endl << name.toUtf8().data() << endl << surname.toUtf8().data() << endl << mail.toStdString() << endl;

                if (name.indexOf(QRegExp("[^A-Za-z]")) != -1)
                {
                    cout << "1" << endl << QString::fromUtf8("Błędne imie").toUtf8().data() << endl << "0" << endl;
                    i++;
                    continue;
                }
                if (surname.indexOf(QRegExp("[^A-Za-z]")) != -1)
                {
                    cout << "1" << endl << QString::fromUtf8("Błędne nazwisko").toUtf8().data() << endl << "0" << endl;
                    i++;
                    continue;
                }
                if (index.indexOf(QRegExp("[^0-9]")) != -1)
                {
                    cout << "1" << endl << QString::fromUtf8("Błędny index").toUtf8().data() << endl << "0" << endl;
                    i++;
                    continue;
                }
                if (!mailPattern.exactMatch(mail))
                {
                    cout << "1" << endl << QString::fromUtf8("Błędny adres email").toUtf8().data() << endl << "0" << endl;
                    i++;
                    continue;
                }

                testQuery.exec(testTemplIndex.arg(QString("indeks"), index));
                if (testQuery.size() != 0)
                {
                    testQuery.next();
                    cout << "1" << endl << QString::fromUtf8("Użytkownik o podanym indeksie jest już w bazie, ale został dodany do grupy").toUtf8().data() << endl << QString::number(testQuery.value(0).toInt()).toAscii().data() << endl;
                    i++;
                    continue;
                }

                testQuery.exec(testTemplMail.arg(QString("mail"), mail));
                if (testQuery.size() != 0)
                {
                    cout << "1" << endl << QString::fromUtf8("Użytkownik o podanym emailu jest już w bazie").toUtf8().data() << endl << "0" << endl;
                    i++;
                    continue;
                }


                statement = templ.arg(name, surname, (QCryptographicHash::hash(pass.toAscii(), QCryptographicHash::Sha1)).toHex(), mail, index);

                if (!insertQuery.exec(statement))
                    cout << "1" << endl << insertQuery.lastError().text().toUtf8().data() << endl << "0" << endl;
                else
                {
                    cout << "0" << endl << passwd << endl << QString::number(insertQuery.lastInsertId().toInt()).toAscii().data() << endl;
                    insert_test++;
                }
		i++;
	}

        if ( remove(tmpName) != 0)
        {
            cout << "Error deleting temp file." << endl;
            return 8;
        }
        if (remove(argv[1]) != 0)
        {
            cout << "Error deleting file " << argv[1] << endl;
            return 9;
        }
        if (insert_test <= 0)
            return 200;
        if (insert_test < rows)
            return 100;
        return 0;
}
