#-------------------------------------------------
#
# Project created by QtCreator 2011-04-15T17:44:57
#
#-------------------------------------------------

QT       += core
QT       += sql

QT       -= gui

TARGET = studentList
CONFIG   += console
CONFIG   -= app_bundle

TEMPLATE = app


SOURCES += main.cpp \
    ExcelFormat.cpp \
    BasicExcel.cpp

HEADERS += \
    ExcelFormat.h \
    BasicExcel.hpp
