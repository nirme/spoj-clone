/////////////////////////////////////////////////////////////
//                                                         //
//  Intrukcja uzycia: jako argument podajesz gotowego      //
//  cssa, w programie wpisujesz nazwe nowego pliku, wybor  //
//  kolorow pozwala na ustawienie stosunku wartosci        //
//  kolorow - przyjmuje kazda dodatnia wartosc calkowita,  //
//  dostosowanie jasnosci mozna pominac, wybrac opcje      //
//  zmniejszenia/zwiekszenia o konkretna wartosc lub       //
//  zeskalowac                          by nirme           //
//                                                         //
/////////////////////////////////////////////////////////////


#define _CRT_SECURE_NO_WARNINGS
#include <stdio.h>
#include <iostream>
#include <fstream>
#include <cmath>

using namespace std;

int main (int argc, char *argv[])
{
	if (argc != 2)
	{
		cout << "nie podales pliku\n";
		system("PAUSE");
		return 0;
	}
	
	fstream cssFile (argv[1], fstream::in | fstream::binary);
	if (!cssFile.is_open())
	{
		cout << "blad otwarcia pliku\n";
		system("PAUSE");
		return 0;
	}
	char newFileStr[256];
	strcpy(newFileStr, argv[1]);
	char *pos = strrchr(newFileStr, '\\');
	pos++;
	cout << "podaj nazwe dla nowego pliku:\n";
	scanf("%s", pos);
	strcat(newFileStr, ".css\0");
	fstream cssFileNew (newFileStr, fstream::out | fstream::binary | fstream::trunc);
	if (!cssFileNew.is_open())
	{
		cout << "blad utworzenia nowego pliku\n";
		system("PAUSE");
		return 0;
	}

	double color1=0, color2=0, color3=0, color=0;
	int test=4, brpl=0, brpw=100;
	cout << "wpisz wartosci kolorow:\nczerwony: ";
	scanf("%u", &color1);
	cout << "zielony: ";
	scanf("%u", &color2);
	cout << "niebieski: ";
	scanf("%u", &color3);

	cout << "\ndostosuj jasnosc: 1-opusc, 2-zmien o wartosc, 3-zmien procentowo ";
	while(test<1 || test>3)
		scanf("%u", &test);
	if (test==2)
	{
		cout << "podaj wartosc:\n";
		scanf("%d", &brpl);
	}
	else if (test==3)
	{
		cout << "podaj wartosc w procantach:\n";
		scanf("%u", &brpw);
	}

	color=color1+color2+color3;

	if (color==0)
	{
		color=1;
		color1=0;
		color2=0;
		color3=0;
	}

	int colorOld=0, colorNew=0, power=0;

	int i=0, last=0, old1=0, old2=0, old3=0, max=10, hlp=0;
	double tmp1=0, tmp2=0, tmp3=0;
	char line[512], hex[8];

	while (cssFile.getline(line, 512))
	{
		i=0;
		while (line[i])
		{
			if (line[i] == '#' && i < 505)
			{
				if (isxdigit(line[i+1]) && isxdigit(line[i+2]) && isxdigit(line[i+3]) && isxdigit(line[i+4]) && isxdigit(line[i+5]) && isxdigit(line[i+6]) && !isxdigit(line[i+7]))
				{
					char * pEnd;
					colorOld = strtol (&(line[i+1]),&pEnd,16);
					old1=colorOld/256/256;
					old2=(colorOld/256)%256;
					old3=colorOld%256;
					power = old1 + old2 + old3;
					tmp1 = (color1/color)*power;
					tmp2 = (color2/color)*power;
					tmp3 = (color3/color)*power;
					if (tmp3 > 255)
						tmp3 = 255;
					if (abs(old1-old2) < max && abs(old3-old2) < max && abs(old3-old1) < max)
					{
						if (old1 < old2 && old1 < old3)
						{
							hlp = old1+old1-old2-old3;
							tmp1 = old1+color1/color*hlp;
							tmp2 = old1+color2/color*hlp;
							tmp3 = old1+color3/color*hlp;
						}
						else if (old2 < old1 && old2 < old3)
						{
							hlp = old2+old2-old1-old3;
							tmp1 = old2+color1/color*hlp;
							tmp2 = old2+color2/color*hlp;
							tmp3 = old2+color3/color*hlp;
						}
						else
						{
							hlp = old3+old3-old2-old1;
							tmp1 = old3+color1/color*hlp;
							tmp2 = old3+color2/color*hlp;
							tmp3 = old3+color3/color*hlp;
						}
					}
					tmp1 = ((tmp1 + brpl) * brpw)/100;
					tmp2 = ((tmp2 + brpl) * brpw)/100;
					tmp3 = ((tmp3 + brpl) * brpw)/100;
					if (tmp1 > 255)
						tmp1 = 255;
					if (tmp2 > 255)
						tmp2 = 255;
					if (tmp3 > 255)
						tmp3 = 255;
					if (tmp1 < 0)
						tmp1 = 0;
					if (tmp2 < 0)
						tmp2 = 0;
					if (tmp3 < 0)
						tmp3 = 0;

					colorNew = int(tmp1)*256*256 + int(tmp2)*256 + int(tmp3);
					sprintf(hex, "%06X", colorNew);
					line[i+1] = hex[0];
					line[i+2] = hex[1];
					line[i+3] = hex[2];
					line[i+4] = hex[3];
					line[i+5] = hex[4];
					line[i+6] = hex[5];
					i+=7;
				}
			}
			i++;
		}
		cssFileNew.write(line, strlen(line));
	}
	cssFile.close();
	cssFileNew.close();
	system ("PAUSE");
	return 0;
}
