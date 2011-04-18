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
// Calendar PL language
// Author: Artur Filipiak, <imagen@poczta.fm>
// January, 2004
// Encoding: UTF-8
Calendar._DN = new Array
("Niedziela", "Poniedziałek", "Wtorek", "Środa", "Czwartek", "Piątek", "Sobota", "Niedziela");

Calendar._SDN = new Array
("N", "Pn", "Wt", "Śr", "Cz", "Pt", "So", "N");

Calendar._MN = new Array
("Styczeń", "Luty", "Marzec", "Kwiecień", "Maj", "Czerwiec", "Lipiec", "Sierpień", "Wrzesień", "Październik", "Listopad", "Grudzień");

Calendar._SMN = new Array
("Sty", "Lut", "Mar", "Kwi", "Maj", "Cze", "Lip", "Sie", "Wrz", "Paź", "Lis", "Gru");

// tooltips
if(Calendar._TT == undefined) Calendar._TT = {};
Calendar._TT["INFO"] = "O kalendarzu";

Calendar._TT["ABOUT"] =
"DHTML Date/Time Selector\n" +
"(c) dynarch.com 2002-2003\n" + // don't translate this this ;-)
"For latest version visit: http://dynarch.com/mishoo/calendar.epl\n" +
"Distributed under GNU LGPL.  See http://gnu.org/licenses/lgpl.html for details." +
"\n\n" +
"Wybór daty:\n" +
"- aby wybrać rok użyj przycisków \xab, \xbb\n" +
"- aby wybrać miesiąc użyj przycisków " + String.fromCharCode(0x2039) + ", " + String.fromCharCode(0x203a) + "\n" +
"- aby przyspieszyć wybór przytrzymaj wciśnięty przycisk myszy nad ww. przyciskami.";
Calendar._TT["ABOUT_TIME"] = "\n\n" +
"Wybór czasu:\n" +
"- aby zwiększyć wartość kliknij na dowolnym elemencie selekcji czasu\n" +
"- aby zmniejszyć wartość użyj dodatkowo klawisza Shift\n" +
"- możesz również poruszać myszkę w lewo i prawo wraz z wciśniętym lewym klawiszem.";

Calendar._TT["PREV_YEAR"] = "Poprz. rok (przytrzymaj dla menu)";
Calendar._TT["PREV_MONTH"] = "Poprz. miesiąc (przytrzymaj dla menu)";
Calendar._TT["GO_TODAY"] = "Pokaż dziś";
Calendar._TT["NEXT_MONTH"] = "Nast. miesiąc (przytrzymaj dla menu)";
Calendar._TT["NEXT_YEAR"] = "Nast. rok (przytrzymaj dla menu)";
Calendar._TT["SEL_DATE"] = "Wybierz datę";
Calendar._TT["DRAG_TO_MOVE"] = "Przesuń okienko";
Calendar._TT["PART_TODAY"] = " (dziś)";
Calendar._TT["MON_FIRST"] = "Pokaż Poniedziałek jako pierwszy";
Calendar._TT["SUN_FIRST"] = "Pokaż Niedzielę jako pierwszą";
Calendar._TT["CLOSE"] = "Zamknij";
Calendar._TT["TODAY"] = "Dziś";
Calendar._TT["TIME_PART"] = "(Shift-)klik | drag, aby zmienić wartość";

// date formats
Calendar._TT["DEF_DATE_FORMAT"] = "%Y.%m.%d";
Calendar._TT["TT_DATE_FORMAT"] = "%a, %b %e";

Calendar._TT["WK"] = "wk";