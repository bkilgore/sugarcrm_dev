/*

Modification information for LGPL compliance

r58364 - 2010-09-29 22:39:05 -0700 (Wed, 29 Sep 2010) - kjing - Author: Stanislav Malyshev <smalyshev@gmail.com>
    Fix languages in JS calendar

r56990 - 2010-06-16 13:05:36 -0700 (Wed, 16 Jun 2010) - kjing - snapshot "Mango" svn branch to a new one for GitHub sync

r56989 - 2010-06-16 13:01:33 -0700 (Wed, 16 Jun 2010) - kjing - defunt "Mango" svn dev branch before github cutover

r55980 - 2010-04-19 13:31:28 -0700 (Mon, 19 Apr 2010) - kjing - create Mango (6.1) based on windex

r51719 - 2009-10-22 10:18:00 -0700 (Thu, 22 Oct 2009) - mitani - Converted to Build 3  tags and updated the build system 

r51634 - 2009-10-19 13:32:22 -0700 (Mon, 19 Oct 2009) - mitani - Windex is the branch for Sugar Sales 1.0 development

r50375 - 2009-08-24 18:07:43 -0700 (Mon, 24 Aug 2009) - dwong - branch kobe2 from tokyo r50372

r42807 - 2008-12-29 11:16:59 -0800 (Mon, 29 Dec 2008) - dwong - Branch from trunk/sugarcrm r42806 to branches/tokyo/sugarcrm

r550 - 2004-09-03 15:39:17 -0700 (Fri, 03 Sep 2004) - jostrow - Calendar feature


*/

// ** I18N
Calendar._DN = new Array
("Duminică",
 "Luni",
 "Marţi",
 "Miercuri",
 "Joi",
 "Vineri",
 "Sâmbătă",
 "Duminică");
Calendar._SDN_len = 2;
Calendar._MN = new Array
("Ianuarie",
 "Februarie",
 "Martie",
 "Aprilie",
 "Mai",
 "Iunie",
 "Iulie",
 "August",
 "Septembrie",
 "Octombrie",
 "Noiembrie",
 "Decembrie");

// tooltips
if(Calendar._TT == undefined) Calendar._TT = {};

Calendar._TT["INFO"] = "Despre calendar";

Calendar._TT["ABOUT"] =
"DHTML Date/Time Selector\n" +
"(c) dynarch.com 2002-2003\n" + // don't translate this this ;-)
"Pentru ultima versiune vizitaţi: http://dynarch.com/mishoo/calendar.epl\n" +
"Distribuit sub GNU LGPL.  See http://gnu.org/licenses/lgpl.html for details." +
"\n\n" +
"Selecţia datei:\n" +
"- Folosiţi butoanele \xab, \xbb pentru a selecta anul\n" +
"- Folosiţi butoanele " + String.fromCharCode(0x2039) + ", " + String.fromCharCode(0x203a) + " pentru a selecta luna\n" +
"- Tineţi butonul mouse-ului apăsat pentru selecţie mai rapidă.";
Calendar._TT["ABOUT_TIME"] = "\n\n" +
"Selecţia orei:\n" +
"- Click pe ora sau minut pentru a mări valoarea cu 1\n" +
"- Sau Shift-Click pentru a micşora valoarea cu 1\n" +
"- Sau Click şi drag pentru a selecta mai repede.";

Calendar._TT["PREV_YEAR"] = "Anul precedent (lung pt menu)";
Calendar._TT["PREV_MONTH"] = "Luna precedentă (lung pt menu)";
Calendar._TT["GO_TODAY"] = "Data de azi";
Calendar._TT["NEXT_MONTH"] = "Luna următoare (lung pt menu)";
Calendar._TT["NEXT_YEAR"] = "Anul următor (lung pt menu)";
Calendar._TT["SEL_DATE"] = "Selectează data";
Calendar._TT["DRAG_TO_MOVE"] = "Trage pentru a mişca";
Calendar._TT["PART_TODAY"] = " (astăzi)";
Calendar._TT["DAY_FIRST"] = "Afişează %s prima zi";
Calendar._TT["WEEKEND"] = "0,6";
Calendar._TT["CLOSE"] = "Închide";
Calendar._TT["TODAY"] = "Astăzi";
Calendar._TT["TIME_PART"] = "(Shift-)Click sau drag pentru a selecta";

// date formats
Calendar._TT["DEF_DATE_FORMAT"] = "%d-%m-%Y";
Calendar._TT["TT_DATE_FORMAT"] = "%A, %d %B";

Calendar._TT["WK"] = "spt";
Calendar._TT["TIME"] = "Ora:";
