USE projekt5;
SET foreign_key_checks = 0 ;

DROP TABLE users, solutions, languages, taskList, taskIO, groups, news, user_to_group, task_to_group;

CREATE TABLE projekt5.users (
id SERIAL,
name VARCHAR(255) NOT NULL,
surname VARCHAR(255) NOT NULL,
login VARCHAR(255) DEFAULT NULL,
pass CHAR(40) NOT NULL,
mail VARCHAR(255) NOT NULL UNIQUE,
indeks DECIMAL(10,0) UNIQUE,
points INT UNSIGNED DEFAULT 0,
PRIMARY KEY (id))
ENGINE = InnoDB, ROW_FORMAT=DYNAMIC
CHARACTER SET = utf8
COLLATE utf8_polish_ci;


CREATE TABLE projekt5.solutions (
id SERIAL,
user_id BIGINT UNSIGNED NOT NULL,
task_id BIGINT UNSIGNED NOT NULL,
make_date DATETIME,
solution TEXT,
lang_id BIGINT UNSIGNED NOT NULL,
points DECIMAL(4,2) DEFAULT 0,
error ENUM('UNDEFINED_ERROR', 'COMPILATION_ERROR', 'WAIT_FOR_RUN', 'RUNTIME_ERROR', 'MIXED_ERROR', 'NO_ERROR') DEFAULT NULL,
error_str TEXT DEFAULT NULL,
PRIMARY KEY (id))
ENGINE = MyISAM, ROW_FORMAT=DYNAMIC
CHARACTER SET = utf8
COLLATE utf8_polish_ci;


CREATE TABLE projekt5.languages (
id SERIAL,
compiler_system_name VARCHAR(32) NOT NULL,
language_name VARCHAR(32) NOT NULL UNIQUE,
compile_string VARCHAR(255),
file_format VARCHAR(25) NOT NULL,
script_language BOOLEAN DEFAULT false,
PRIMARY KEY (id))
ENGINE = MyISAM, ROW_FORMAT=DYNAMIC
CHARACTER SET = utf8
COLLATE utf8_polish_ci;


CREATE TABLE projekt5.taskList (
id SERIAL,
makerId BIGINT UNSIGNED NOT NULL,
points DECIMAL(4,2) DEFAULT 0,
title VARCHAR(255) NOT NULL UNIQUE,
makeDate DATE,
runTime DECIMAL(6,0) NOT NULL DEFAULT 3000,
description TEXT NOT NULL,
PRIMARY KEY (id))
ENGINE = InnoDB, ROW_FORMAT=DYNAMIC
CHARACTER SET = utf8
COLLATE utf8_polish_ci;


CREATE TABLE projekt5.taskIO (
id SERIAL,
task_id BIGINT UNSIGNED NOT NULL,
arguments TEXT,
input_string TEXT,
output_string TEXT,
return_value INT DEFAULT 0,
PRIMARY KEY (id),
FOREIGN KEY (task_id) REFERENCES taskList(id) ON DELETE CASCADE ON UPDATE CASCADE)
ENGINE = InnoDB, ROW_FORMAT=DYNAMIC
CHARACTER SET = utf8
COLLATE utf8_polish_ci;



CREATE TABLE projekt5.groups (
id SERIAL,
name VARCHAR(30) UNIQUE,
PRIMARY KEY (id))
ENGINE = InnoDB, ROW_FORMAT=DYNAMIC
CHARACTER SET = utf8
COLLATE utf8_polish_ci;


CREATE TABLE projekt5.user_to_group (
group_id BIGINT UNSIGNED NOT NULL,
user_id BIGINT UNSIGNED NOT NULL,
PRIMARY KEY (group_id, user_id),
FOREIGN KEY (group_id) REFERENCES groups(id) ON DELETE CASCADE ON UPDATE CASCADE,
FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE)
ENGINE = InnoDB, ROW_FORMAT=DYNAMIC;



CREATE TABLE projekt5.task_to_group (
task_id BIGINT UNSIGNED NOT NULL,
group_id BIGINT UNSIGNED NOT NULL,
last_chk DATE DEFAULT NULL,
PRIMARY KEY (task_id, group_id),
FOREIGN KEY (group_id) REFERENCES groups(id) ON DELETE CASCADE ON UPDATE CASCADE,
FOREIGN KEY (task_id) REFERENCES taskList(id) ON DELETE CASCADE ON UPDATE CASCADE)
ENGINE = InnoDB, ROW_FORMAT=DYNAMIC;


CREATE TABLE projekt5.news (
id SERIAL,
title VARCHAR(255),
news_text TEXT,
author_id BIGINT UNSIGNED NOT NULL,
clock DATETIME NOT NULL,
PRIMARY KEY (id))
ENGINE = MyISAM, ROW_FORMAT=DYNAMIC
CHARACTER SET = utf8
COLLATE utf8_polish_ci;



insert projekt5.users (name, surname, login, pass, mail, indeks) values ('Przemyslaw', 'Brzoska', 'nirme', sha1('password'), 'nirme.xxx', -1);
insert projekt5.users (name, surname, login, pass, mail, indeks) values ('Kamil', 'Karwacki', 'kamil', sha1('password'), 'kamil.xxx', -8);
insert projekt5.users (name, surname, login, pass, mail, indeks) values ('user1-N', 'test1-sN', 'test1', sha1('password'), 'userdqwertasddasdasasdadfghj.xxx', 12345);
insert projekt5.users (name, surname, login, pass, mail, indeks) values ('user2-N', 'test2-sN', 'test2', sha1('password'), 'user2.xxx', 52345);
insert projekt5.users (name, surname, login, pass, mail, indeks) values ('user3-N', 'test3-sN', 'test3', sha1('password'), 'user3.xxx', 72345);
insert projekt5.users (name, surname, login, pass, mail, indeks) values ('user4-N', 'test4-sN', 'test4', sha1('password'), 'user4.xxx', 72335);
insert projekt5.users (name, surname, login, pass, mail, indeks) values ('user5-N', 'test5-sN', 'test5', sha1('password'), 'user5.xxx', 74345);
insert projekt5.users (name, surname, login, pass, mail, indeks) values ('usdsadssN', 'jnhgbhefafs', 'test6', sha1('password'), 'user6.xxx', 74355);

insert projekt5.groups (name) values ('Test_group1');
insert projekt5.groups (name) values ('Test_group2');
insert projekt5.groups (name) values ('Test_group3');

insert projekt5.user_to_group (group_id, user_id) values (1, 2);
insert projekt5.user_to_group (group_id, user_id) values (1, 3);

insert projekt5.user_to_group (group_id, user_id) values (2, 5);
insert projekt5.user_to_group (group_id, user_id) values (2, 6);

insert projekt5.task_to_group (task_id, group_id) values (1, 1);
insert projekt5.task_to_group (task_id, group_id) values (2, 1);
insert projekt5.task_to_group (task_id, group_id) values (3, 1);
insert projekt5.task_to_group (task_id, group_id) values (4, 1);

insert projekt5.task_to_group (task_id, group_id) values (6, 2);
insert projekt5.task_to_group (task_id, group_id) values (7, 2);
insert projekt5.task_to_group (task_id, group_id) values (8, 2);



insert projekt5.languages (compiler_system_name, language_name, compile_string, file_format) values ('gcc 4.4.5', 'c', 'gcc -x c \'%1\' -o \'%2\'', '.c\n');
insert projekt5.languages (compiler_system_name, language_name, compile_string, file_format) values ('gcc 4.4.5', 'c++', 'gcc -x c++ \'%1\' -o \'%2\'', '.cpp\n');
insert projekt5.languages (compiler_system_name, language_name, compile_string, file_format) values ('fpc 2.4.0-2', 'pascal', 'fpc \'%1\' -o\'%2\'', '.d\n');


insert projekt5.taskList (makerId, points, title, makeDate, runTime, description) values (1, 10, 'Life, the Universe, and Everything',
'2010-02-03', 1000, 'Your program is to use the brute-force approach in order to find the Answer to Life,
the Universe, and Everything. More precisely... rewrite small numbers from input to output.
Stop processing input after reading in the number 42. All numbers at input are integers of one or two digits.<br/>\n<br/>\n
<b>Example</b><br/>\n<br/>\nInput:<br/>\n1<br/>\n2<br/>\n88<br/>\n42<br/>\n99<br/>\n<br/>\nOutput:<br/>\n1<br/>\n2<br/>\n88<br/>\n');

insert projekt5.taskList (makerId, points, title, makeDate, runTime, description) values (1, 15, 'Prime Generator', '2010-04-26', 1000,
'Peter wants to generate some prime numbers for his cryptosystem. Help him!
Your task is to generate all prime numbers between two given numbers!<br/>\n<br/>\n<b>Input</b><br/>\n<br/>\n
The input begins with the number t of test cases in a single line (t<=10).
In each of the next t lines there are two numbers m and n (1 <= m <= n <= 1000000000, n-m<=100000) separated by a space.
<br/>\n<br/>\n<b>Output</b><br/>\n<br/>\n
For every test case print all prime numbers p such that m <= p <= n, one number per line, test cases separated by an empty line.
<br/>\n<br/>\n<b>Example</b><br/>\n<br/>\n<b>Input:</b><br/>\n2<br/>\n1 10<br/>\n3 5<br/>\n
<br/>\n<br/>\n<b>Output:</b><br/>\n2<br/>\n3<br/>\n5<br/>\n7<br/>\n<br/>\n3<br/>\n5<br/>\n');

insert projekt5.taskList (makerId, points, makeDate, runTime, title, description) values (1, 20, '2008-05-04', 4000, 
'Substring Check (Bug Funny)', 
"Given two binary strings, A (of length 10) and B (of length 5), output 1 if B is a substring of A and 0 otherwise.<br/>\n<br/>\n
Please note, that the solution may only be submitted in the following languages: Brainf**k, Whitespace and Intercal.<br/>\n<br/>\n
<b>Input</b><br/>\n<br/>\n24 lines consisting of pairs of binary strings A and B separated by a single space.<br/>\n<br/>\n
<b>Output</b><br/>\n<br/>\nThe logical value of: 'B is a substring of A'.<br/>\n<br/>\n<b>Example</b><br/>\n<br/>\n
First two lines of input:<br/>\n1010110010 10110<br/>\n1110111011 10011<br/>\nFirst two lines of output:<br/>\n1<br/>\n0<br/>\n");

insert projekt5.taskList (makerId, points, makeDate, runTime, title, description) values (1, 20, '2008-05-04', 1000, 
'Transform the Expression', 
"Transform the algebraic expression with brackets into RPN form (Reverse Polish Notation).
 Two-argument operators: +, -, *, /, ^ (priority from the lowest to the highest), brackets ( ).
 Operands: only letters: a,b,...,z. Assume that there is only one RPN form (no expressions like a*b*c).<br/>\n<br/>\n
<b>Input</b><br/>\n<br/>\nt [the number of expressions <= 100]<br/>\nexpression [length <= 400]<br/>\n[other expressions]<br/>\n
Text grouped in [ ] does not appear in the input file.<br/>\n<br/>\n<b>Output</b><br/>\n<br/>\nThe expressions in RPN form, one per line.<br/>\n
Example<br/>\n<br/>\nInput:<br/>\n3<br/>\n(a+(b*c))<br/>\n((a+b)*(z+x))<br/>\n((a+t)*((b+(a+c))^(c+d)))<br/>\n<br/>\n
Output:<br/>\nabc*+<br/>\nab+zx+*<br/>\nat+bac++cd+^*<br/>\n");

insert projekt5.taskList (makerId, points, makeDate, runTime, title, description) values (1, 20, '2008-05-04', 9000, 
'The Next Palindrome', 
"A positive integer is called a palindrome if its representation in the decimal system is the same when read from left
 to right and from right to left. For a given positive integer K of not more than 1000000 digits, write the value of the 
 smallest palindrome larger than K to output. Numbers are always displayed without leading zeros.<br/>\n<br/>\n
<b>Input</b><br/>\n<br/>\nThe first line contains integer t, the number of test cases. Integers K are given in the next t lines.<br/>\n<br/>\n
<b>Output</b><br/>\n<br/>\nFor each K, output the smallest palindrome larger than K.<br/>\n<br/>\n<b>Example</b><br/>\n<br/>\n
Input:<br/>\n2<br/>\n808<br/>\n2133<br/>\nOutput:<br/>\n818<br/>\n2222<br/>\n");

insert projekt5.taskList (makerId, points, makeDate, runTime, title, description) values (1, 20, '2008-05-04', 6000, 
'Simple Arithmetics', 
"One part of the new WAP portal is also a calculator computing expressions with very long numbers. To make the output look better, 
the result is formated the same way as is it usually used with manual calculations.<br/>\n<br/>\n
Your task is to write the core part of this calculator. Given two numbers and the requested operation, you are to compute the result 
and print it in the form specified below. With addition and subtraction, the numbers are written below each other. Multiplication is 
a little bit more complex: first of all, we make a partial result for every digit of one of the numbers, and then sum the results together.
<br/>\n<br/>\n<b>Input</b><br/>\n<br/>\nThere is a single positive integer T on the first line of input (equal to about 1000). It stands for the 
number of expressions to follow. Each expression consists of a single line containing a positive integer number, an operator (one of +, - 
and *) and the second positive integer number. Every number has at most 500 digits. There are no spaces on the line. If the operation is 
subtraction, the second number is always lower than the first one. No number will begin with zero.<br/>\n<br/>\n<b>Output</b><br/>\n<br/>\n
For each expression, print two lines with two given numbers, the second number below the first one, last digits (representing unities) 
must be aligned in the same column. Put the operator right in front of the first digit of the second number. After the second number, 
there must be a horizontal line made of dashes (-).<br/>\n<br/>\n
For each addition or subtraction, put the result right below the horizontal line, with last digit aligned to the last digit of both operands.
<br/>\n<br/>\nFor each multiplication, multiply the first number by each digit of the second number. Put the partial results one below the other, 
starting with the product of the last digit of the second number. Each partial result should be aligned with the corresponding digit. 
That means the last digit of the partial product must be in the same column as the digit of the second number. No product may begin with 
any additional zeros. If a particular digit is zero, the product has exactly one digit -- zero. If the second number has more than one 
digit, print another horizontal line under the partial results, and then print the sum of them.<br/>\n<br/>\n
There must be minimal number of spaces on the beginning of lines, with respect to other constraints. The horizontal line is always as long as 
necessary to reach the left and right end of both numbers (and operators) directly below and above it. That means it begins in the same column 
where the leftmost digit or operator of that two lines (one below and one above) is. It ends in the column where is the rightmost digit of that 
two numbers. The line can be neither longer nor shorter than specified.<br/>\n<br/>\n
Print one blank line after each test case, including the last one.<br/>\n<br/>\n<b>Example</b><br/>\nSample Input:<br/>\n<br/>\n4<br/>\n
12345+67890<br/>\n324-111<br/>\n325*4405<br/>\n1234*4<br/>\n<br/>\nSample Output:<br/>\n<br/>\n 12345<br/>\n+67890<br/>\n------<br/>\n
 80235<br/>\n<br/>\n 324<br/>\n-111<br/>\n----<br/>\n 213<br/>\n<br/>\n	325<br/>\n  *4405<br/>\n  -----<br/>\n   1625<br/>\n	 0<br/>\n
 1300<br/>\n1300<br/>\n-------<br/>\n1431625<br/>\n<br/>\n1234<br/>\n  *4<br/>\n----<br/>\n4936<br/>\n");

insert projekt5.taskList (makerId, points, makeDate, runTime, title, description) values (1, 20, '2009-08-13', 7000, 
'The Bulk!', "ACM uses a new special technology of building its transceiver stations. This technology is called Modular Cuboid Architecture 
(MCA) and is covered by a patent of Lego company. All parts of the transceiver are shipped in unit blocks that have the form of cubes of exactly 
the same size. The cubes can be then connected to each other. The MCA is modular architecture, that means we can select preferred transceiver 
configuration and buy only those components we need .<br/>\n<br/>\nThe cubes must be always connected \"face-to-face\", i.e. the whole side of 
one cube is connected to the whole side of another cube. One cube can be thus connected to at most six other units. The resulting equipment, 
consisting of unit cubes is called The Bulk in the communication technology slang.<br/>\n<br/>\n
Sometimes, an old and unneeded bulk is condemned, put into a storage place, and replaced with a new one. It was recently found that ACM has 
many of such old bulks that just occupy space and are no longer needed. The director has decided that all such bulks must be disassembled to 
single pieces to save some space. Unfortunately, there is no documentation for the old bulks and nobody knows the exact number of pieces that 
form them. You are to write a computer program that takes the bulk description and computes the number of unit cubes.<br/>\n<br/>\n
Each bulk is described by its faces (sides). A special X-ray based machine was constructed that is able to localise all faces of the bulk in 
the space, even the inner faces, because the bulk can be partially hollow (it can contain empty spaces inside). But any bulk must be connected 
(i.e. it cannot drop into two pieces) and composed of whole unit cubes.<br/>\n<br/>\n<br/>\n<b>Input</b><br/>\n<br/>\nThere is a single 
positive integer T on the first line of input (equal to about 1000). It stands for the number of bulks to follow. Each bulk description 
begins with a line containing single positive integer F, 6 <= F <= 250, stating the number of faces. Then there are F lines, each containing 
one face description. All faces of the bulk are always listed, in any order. Any face may be divided into several distinct parts and described 
like if it was more faces. Faces do not overlap. Every face has one inner side and one outer side. No side can be \"partially inner and 
partially outer\".<br/>\n<br/>\nEach face is described on a single line. The line begins with an integer number P stating the number of points 
that determine the face, 4 <= P <= 200. Then there are 3 x P numbers, coordinates of the points. Each point is described by three coordinates 
X,Y,Z (0 <= X,Y,Z <= 1000) separated by spaces. The points are separated from each other and from the number P by two space characters. 
These additional spaces were added to make the input more human readable. The face can be constructed by connecting the points in the 
specified order, plus connecting the last point with the first one.<br/>\n<br/>\nThe face is always composed of \"unit squares\", that means 
every edge runs either in X, Y or Z-axis direction. If we take any two neighbouring points X1,Y1,Z1 and X2,Y2,Z2, then the points will always 
differ in exactly one of the three coordinates. I.e. it is either X1 <> X2, or Y1 <> Y2, or Z1 <> Z2, other two coordinates are the same. 
Every face lies in an orthogonal plane, i.e. exactly one coordinate is always the same for all points of the face. The face outline will 
never touch nor cross itself.<br/>\n<br/>\n<br/>\n<b>Output</b><br/>\n<br/>\nYour program must print a single line for every test case. 
The line must contain the sentence The bulk is composed of V units., where V is the volume of the bulk.<br/>\n<br/>\n<b>Example</b><br/>\n<br/>\n
Sample Input:<br/>\n<br/>\n2<br/>\n12<br/>\n4  10 10 10  10 10 20  10 20 20  10 20 10<br/>\n4  20 10 10  20 10 20  20 20 20  20 20 10<br/>\n
4  10 10 10  10 10 20  20 10 20  20 10 10<br/>\n4  10 20 10  10 20 20  20 20 20  20 20 10<br/>\n4  10 10 10  10 20 10  20 20 10  20 10 10<br/>\n
5  10 10 20  10 20 20  20 20 20  20 15 20  20 10 20<br/>\n4  14 14 14  14 14 16  14 16 16  14 16 14<br/>\n
4  16 14 14  16 14 16  16 16 16  16 16 14<br/>\n4  14 14 14  14 14 16  16 14 16  16 14 14<br/>\n4  14 16 14  14 16 16  16 16 16  16 16 14<br/>\n
4  14 14 14  14 16 14  16 16 14  16 14 14<br/>\n4  14 14 16  14 16 16  16 16 16  16 14 16<br/>\n12<br/>\n
4  20 20 30  20 30 30  30 30 30  30 20 30<br/>\n4  10 10 10  10 40 10  40 40 10  40 10 10<br/>\n
6  10 10 20  20 10 20  20 30 20  30 30 20  30 40 20  10 40 20<br/>\n6  20 10 20  20 20 20  30 20 20  30 40 20  40 40 20  40 10 20<br/>\n
4  10 10 10  40 10 10  40 10 20  10 10 20<br/>\n4  10 40 10  40 40 10  40 40 20  10 40 20<br/>\n4  20 20 20  30 20 20  30 20 30  20 20 30<br/>\n
4  20 30 20  30 30 20  30 30 30  20 30 30<br/>\n4  10 10 10  10 40 10  10 40 20  10 10 20<br/>\n4  40 10 10  40 40 10  40 40 20  40 10 20<br/>\n
4  20 20 20  20 30 20  20 30 30  20 20 30<br/>\n4  30 20 20  30 30 20  30 30 30  30 20 30<br/>\n<br/>\nSample Output:<br/>\n<br/>\n
The bulk is composed of 992 units.<br/>\nThe bulk is composed of 10000 units.<br/>\n");

insert projekt5.taskList (makerId, points, makeDate, runTime, title, description) values (1, 20, '2004-05-08', 5000, 
'Complete the Sequence!', 
"You probably know those quizzes in Sunday magazines: given the sequence 1, 2, 3, 4, 5, what is the next number? Sometimes it is very easy to 
answer, sometimes it could be pretty hard. Because these \"sequence problems\" are very popular, ACM wants to implement them into the 
\"Free Time\" section of their new WAP portal.<br/>\n<br/>\nACM programmers have noticed that some of the quizzes can be solved by describing 
the sequence by polynomials. For example, the sequence 1, 2, 3, 4, 5 can be easily understood as a trivial polynomial. The next number is 6. 
But even more complex sequences, like 1, 2, 4, 7, 11, can be described by a polynomial. In this case, 1/2.n2-1/2.n+1 can be used. Note that 
even if the members of the sequence are integers, polynomial coefficients may be any real numbers.<br/>\n<br/>\n
Polynomial is an expression in the following form: <br/>\n<br/>\nP(n) = aD.nD+aD-1.nD-1+...+a1.n+a0<br/>\n<br/>\n
If aD <> 0, the number D is called a degree of the polynomial. Note that constant function P(n) = C can be considered as polynomial of degree 0, 
and the zero function P(n) = 0 is usually defined to have degree -1.<br/>\n<br/>\n<b>Input</b>There is a single positive integer T on the first 
line of input (equal to about 5000). It stands for the number of test cases to follow. Each test case consists of two lines. First line of each 
test case contains two integer numbers S and C separated by a single space, 1 <= S < 100, 1 <= C < 100, (S+C) <= 100. The first number, S, 
stands for the length of the given sequence, the second number, C is the amount of numbers you are to find to complete the sequence.
<br/>\n<br/>\nThe second line of each test case contains S integer numbers X1, X2, ... XS separated by a space. These numbers form the given 
sequence. The sequence can always be described by a polynomial P(n) such that for every i, Xi = P(i). Among these polynomials, we can find the 
polynomial Pmin with the lowest possible degree. This polynomial should be used for completing the sequence.<br/>\n<br/>\n<b>Output</b>
<br/>\n<br/>\nFor every test case, your program must print a single line containing C integer numbers, separated by a space. These numbers 
are the values completing the sequence according to the polynomial of the lowest possible degree. In other words, you are to print values 
Pmin(S+1), Pmin(S+2), .... Pmin(S+C).<br/>\n<br/>\nIt is guaranteed that the results Pmin(S+i) will be non-negative and will fit into the 
standard integer type.<br/>\n<br/>\n<b>Example</b><br/>\n<br/>\nSample Input:<br/>\n<br/>\n4<br/>\n6 3<br/>\n1 2 3 4 5 6<br/>\n8 2<br/>\n
1 2 4 7 11 16 22 29<br/>\n10 2<br/>\n1 1 1 1 1 1 1 1 1 2<br/>\n1 10<br/>\n3<br/>\n<br/>\nSample Output:<br/>\n<br/>\n
7 8 9<br/>\n37 46<br/>\n11 56<br/>\n3 3 3 3 3 3 3 3 3 3<br/>\n");




insert projekt5.taskIO (task_id, arguments, input_string, output_string, return_value)
values (1, '', '1\n2\n88\n42\n99\n', '1\n2\n88\n', 0);

insert projekt5.taskIO (task_id, arguments, input_string, output_string, return_value)
values (2, '', '2\n1 10\n3 5\n', '2\n3\n5\n7\n\n3\n5\n', 0);

insert projekt5.taskIO (task_id, arguments, return_value, input_string, output_string)
values (3, '', 0, '1010110010 10110\n1110111011 10011\n', '1\n0\n');

insert projekt5.taskIO (task_id, arguments, return_value, input_string, output_string)
values (4, '', 0, '3\n(a+(b*c))\n((a+b)*(z+x))\n((a+t)*((b+(a+c))^(c+d)))\n', 'abc*+\nab+zx+*\nat+bac++cd+^*\n');

insert projekt5.taskIO (task_id, arguments, return_value, input_string, output_string)
values (5, '', 0, '2\n808\n2133\n', '818\n2222\n');

insert projekt5.taskIO (task_id, arguments, return_value, input_string, output_string)
values (6, '', 0, '4\n12345+67890\n324-111\n325*4405\n1234*4\n', ' 12345\n+67890\n------\n 80235\n\n 324\n-111\n----\n 213\n
\n	325\n  *4405\n  -----\n   1625\n	 0\n 1300\n1300\n-------\n1431625\n\n1234\n  *4\n----\n4936\n');

insert projekt5.taskIO (task_id, arguments, return_value, input_string, output_string)
values (7, '', 0, '2\n12\n4  10 10 10  10 10 20  10 20 20  10 20 10\n4  20 10 10  20 10 20  20 20 20  20 20 10\n
4  10 10 10  10 10 20  20 10 20  20 10 10\n4  10 20 10  10 20 20  20 20 20  20 20 10\n4  10 10 10  10 20 10  20 20 10  20 10 10\n
5  10 10 20  10 20 20  20 20 20  20 15 20  20 10 20\n4  14 14 14  14 14 16  14 16 16  14 16 14\n4  16 14 14  16 14 16  16 16 16  16 16 14\n
4  14 14 14  14 14 16  16 14 16  16 14 14\n4  14 16 14  14 16 16  16 16 16  16 16 14\n4  14 14 14  14 16 14  16 16 14  16 14 14\n
4  14 14 16  14 16 16  16 16 16  16 14 16\n12\n4  20 20 30  20 30 30  30 30 30  30 20 30\n4  10 10 10  10 40 10  40 40 10  40 10 10\n
6  10 10 20  20 10 20  20 30 20  30 30 20  30 40 20  10 40 20\n6  20 10 20  20 20 20  30 20 20  30 40 20  40 40 20  40 10 20\n
4  10 10 10  40 10 10  40 10 20  10 10 20\n4  10 40 10  40 40 10  40 40 20  10 40 20\n4  20 20 20  30 20 20  30 20 30  20 20 30\n
4  20 30 20  30 30 20  30 30 30  20 30 30\n4  10 10 10  10 40 10  10 40 20  10 10 20\n4  40 10 10  40 40 10  40 40 20  40 10 20\n
4  20 20 20  20 30 20  20 30 30  20 20 30\n4  30 20 20  30 30 20  30 30 30  30 20 30\n',
'The bulk is composed of 992 units.\nThe bulk is composed of 10000 units.\n');

insert projekt5.taskIO (task_id, arguments, return_value, input_string, output_string)
values (8, '', 0, '4\n6 3\n1 2 3 4 5 6\n8 2\n1 2 4 7 11 16 22 29\n10 2\n1 1 1 1 1 1 1 1 1 2\n1 10\n3\n', 
'7 8 9\n37 46\n11 56\n3 3 3 3 3 3 3 3 3 3\n');


insert projekt5.news (title, news_text, author_id, clock) values ('Test_news1_title', 'Test_news1_text<br/>\n<b>Test_news1_text</b><br/>\nTest_news1_text<br/>\n', 1, "2010-01-01 00:00:01");
insert projekt5.news (title, news_text, author_id, clock) values ('Test_news2_title', 'Test_news2_text<br/>\n<b>Test_news2_text</b><br/>\nTest_news2_text<br/>\n', 1, "2010-02-01 00:00:01");
insert projekt5.news (title, news_text, author_id, clock) values ('Test_news3_title', 'Test_news3_text<br/>\n<b>Test_news3_text</b><br/>\nTest_news3_text<br/>\n', 1, "2010-03-01 00:00:01");
insert projekt5.news (title, news_text, author_id, clock) values ('Test_news4_title', 'Test_news4_text<br/>\n<b>Test_news4_text</b><br/>\nTest_news4_text<br/>\n', 1, "2010-04-01 00:00:01");
insert projekt5.news (title, news_text, author_id, clock) values ('Test_news5_title', 'Test_news5_text<br/>\n<b>Test_news5_text</b><br/>\nTest_news5_text<br/>\n', 1, "2010-05-01 00:00:01");
insert projekt5.news (title, news_text, author_id, clock) values ('Test_news6_title', 'Test_news6_text<br/>\n<b>Test_news6_text</b><br/>\nTest_news6_text<br/>\n', 1, "2010-06-01 00:00:01");
insert projekt5.news (title, news_text, author_id, clock) values ('Test_news7_title', 'Test_news7_text<br/>\n<b>Test_news7_text</b><br/>\nTest_news7_text<br/>\n', 1, "2010-07-01 00:00:01");
insert projekt5.news (title, news_text, author_id, clock) values ('Test_news8_title', 'Test_news8_text<br/>\n<b>Test_news8_text</b><br/>\nTest_news8_text<br/>\n', 1, "2010-08-01 00:00:01");
insert projekt5.news (title, news_text, author_id, clock) values ('Test_news9_title', 'Test_news9_text<br/>\n<b>Test_news9_text</b><br/>\nTest_news9_text<br/>\n', 1, "2010-09-01 00:00:01");
insert projekt5.news (title, news_text, author_id, clock) values ('Test_news10_title', 'Test_news10_text<br/>\n<b>Test_news10_text</b><br/>\nTest_news10_text<br/>\n', 1, "2010-10-01 00:00:01");
insert projekt5.news (title, news_text, author_id, clock) values ('Test_news11_title', 'Test_news11_text<br/>\n<b>Test_news11_text</b><br/>\nTest_news11_text<br/>\n', 1, "2010-11-01 00:00:01");
insert projekt5.news (title, news_text, author_id, clock) values ('Test_news12_title', 'Test_news12_text<br/>\n<b>Test_news12_text</b><br/>\nTest_news12_text<br/>\n', 1, "2010-12-01 00:00:01");
insert projekt5.news (title, news_text, author_id, clock) values ('Test_news13_title', 'Test_news13_text<br/>\n<b>Test_news13_text</b><br/>\nTest_news13_text<br/>\n', 1, "2011-01-01 00:00:01");
insert projekt5.news (title, news_text, author_id, clock) values ('Test_news14_title', 'Test_news14_text<br/>\n<b>Test_news14_text</b><br/>\nTest_news14_text<br/>\n', 1, "2011-02-01 00:00:01");
insert projekt5.news (title, news_text, author_id, clock) values ('Test_news15_title', 'Test_news15_text<br/>\n<b>Test_news15_text</b><br/>\nTest_news15_text<br/>\n', 1, "2011-03-01 00:00:01");
insert projekt5.news (title, news_text, author_id, clock) values ('Test_news16_title', 'Test_news16_text<br/>\n<b>Test_news16_text</b><br/>\nTest_news16_text<br/>\n', 1, "2011-04-01 00:00:01");
insert projekt5.news (title, news_text, author_id, clock) values ('Test_news17_title', 'Test_news17_text<br/>\n<b>Test_news17_text</b><br/>\nTest_news17_text<br/>\n', 1, "2011-05-01 00:00:01");
insert projekt5.news (title, news_text, author_id, clock) values ('Test_news18_title', 'Test_news18_text<br/>\n<b>Test_news18_text</b><br/>\nTest_news18_text<br/>\n', 1, "2011-06-01 00:00:01");
insert projekt5.news (title, news_text, author_id, clock) values ('Test_news19_title', 'Test_news19_text<br/>\n<b>Test_news19_text</b><br/>\nTest_news19_text<br/>\n', 1, "2011-07-01 00:00:01");
insert projekt5.news (title, news_text, author_id, clock) values ('Test_news20_title', 'Test_news20_text<br/>\n<b>Test_news20_text</b><br/>\nTest_news20_text<br/>\n', 1, "2011-08-01 00:00:01");

SET foreign_key_checks = 1 ;
