/*

Modification information for LGPL compliance

r58364 - 2010-09-29 22:39:05 -0700 (Wed, 29 Sep 2010) - kjing - Author: Stanislav Malyshev <smalyshev@gmail.com>
    Fix languages in JS calendar

r58354 - 2010-09-29 14:39:04 -0700 (Wed, 29 Sep 2010) - kjing - Author: Majed Itani <mitani@mitani.local>
     fixes issues where language pack calendars were not compatible

r56990 - 2010-06-16 13:05:36 -0700 (Wed, 16 Jun 2010) - kjing - snapshot "Mango" svn branch to a new one for GitHub sync

r56989 - 2010-06-16 13:01:33 -0700 (Wed, 16 Jun 2010) - kjing - defunt "Mango" svn dev branch before github cutover

r55980 - 2010-04-19 13:31:28 -0700 (Mon, 19 Apr 2010) - kjing - create Mango (6.1) based on windex

r51719 - 2009-10-22 10:18:00 -0700 (Thu, 22 Oct 2009) - mitani - Converted to Build 3  tags and updated the build system 

r51634 - 2009-10-19 13:32:22 -0700 (Mon, 19 Oct 2009) - mitani - Windex is the branch for Sugar Sales 1.0 development

r50375 - 2009-08-24 18:07:43 -0700 (Mon, 24 Aug 2009) - dwong - branch kobe2 from tokyo r50372

r42807 - 2008-12-29 11:16:59 -0800 (Mon, 29 Dec 2008) - dwong - Branch from trunk/sugarcrm r42806 to branches/tokyo/sugarcrm

r550 - 2004-09-03 15:39:17 -0700 (Fri, 03 Sep 2004) - jostrow - Calendar feature


*/

// Author: Hartwig Weinkauf h_weinkauf@gmx.de
// �erarbeitet und fehlende Texte hinzugefgt von Gerhard Neinert (gerhard at neinert punkt de)
// Feel free to use / redistribute under the GNU LGPL.
// ** I18N

// short day names
Calendar._SDN = new Array
("So",
 "Mo",
 "Di",
 "Mi",
 "Do",
 "Fr",
 "Sa",
 "So");

// full day names
Calendar._DN = new Array
("Sonntag",
 "Montag",
 "Dienstag",
 "Mittwoch",
 "Donnerstag",
 "Freitag",
 "Samstag",
 "Sonntag");

// short day names only use 2 letters instead of 3
Calendar._SDN_len = 2;

// full month names
Calendar._MN = new Array
("Januar",
 "Februar",
 "M\u00e4rz",
 "April",
 "Mai",
 "Juni",
 "Juli",
 "August",
 "September",
 "Oktober",
 "November",
 "Dezember");

// short month names
Calendar._SMN = new Array
("Jan",
 "Feb",
 "M\u00e4r",
 "Apr",
 "Mai",
 "Jun",
 "Jul",
 "Aug",
 "Sep",
 "Okt",
 "Nov",
 "Dez");

// tooltips
if(Calendar._TT == undefined) Calendar._TT = {};


Calendar._TT["ABOUT"] =
"DHTML Datum/Zeit Selector\n" +
"(c) dynarch.com 2002-2003\n" + // don't translate this this ;-)
"Donwload neueste Version: http://dynarch.com/mishoo/calendar.epl\n" +
"Distributed under GNU LGPL.  See http://gnu.org/licenses/lgpl.html for details." +
"\n\n" +
"Datumsauswahl:\n" +
"- Jahr ausw\u00e4hlen mit \xab und \xbb\n" +
"- Monat ausw\u00e4hlen mit " + String.fromCharCode(0x2039) + " und " + String.fromCharCode(0x203a) + "\n" +
"- Fr Auswahl aus Liste Maustaste gedr\u00fcckt halten.";

Calendar._TT["ABOUT_TIME"] = "\n\n" +
"Zeit w\u00e4hlen:\n" +
"- Stunde/Minute weiter mit Mausklick\n" +
"- Stunde/Minute zurck mit Shift-Mausklick\n" +
"- oder f\u00fcr schnellere Auswahl nach links oder rechts ziehen.";


Calendar._TT["TOGGLE"] = "Ersten Tag der Woche w\u00e4hlen";
Calendar._TT["PREV_YEAR"] = "Jahr zur\u00fcck (halten -> Auswahlmen\u00fc)";
Calendar._TT["PREV_MONTH"] = "Monat zur\u00fcck (halten -> Auswahlmen\u00fc)";
Calendar._TT["GO_TODAY"] = "Gehe zum heutigen Datum";
Calendar._TT["NEXT_MONTH"] = "Monat vor (halten -> Auswahlmen\u00fc)";
Calendar._TT["NEXT_YEAR"] = "Jahr vor (halten -> Auswahlmen\u00fc)";
Calendar._TT["SEL_DATE"] = "Datum ausw\u00e4hlen";
Calendar._TT["DRAG_TO_MOVE"] = "Klicken und halten um zu verschieben";
Calendar._TT["PART_TODAY"] = " (heute)";
Calendar._TT["MON_FIRST"] = "Wochenanzeige mit Montag beginnen";
Calendar._TT["SUN_FIRST"] = "Wochenanzeige mit Sonntag beginnen";
Calendar._TT["CLOSE"] = "Schlie\u00dfen";
Calendar._TT["TODAY"] = "Heute";
Calendar._TT["WEEKEND"] = "0,6";

// date formats
Calendar._TT["DEF_DATE_FORMAT"] = "dd-mm-y";
Calendar._TT["TT_DATE_FORMAT"] = "%a, %b %e";

Calendar._TT["WK"] = "KW";
