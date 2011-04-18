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

r48025 - 2009-06-03 21:56:58 -0700 (Wed, 03 Jun 2009) - weidong - 27345, change the langfiles to be utf-8 no BOM

r42807 - 2008-12-29 11:16:59 -0800 (Mon, 29 Dec 2008) - dwong - Branch from trunk/sugarcrm r42806 to branches/tokyo/sugarcrm

r550 - 2004-09-03 15:39:17 -0700 (Fri, 03 Sep 2004) - jostrow - Calendar feature


*/

// ** I18N

// Calendar FI language (Finnish, Suomi)
// Author: Jarno Käyhkö, <gambler@phnet.fi>
// Encoding: UTF-8
// Distributed under the same terms as the calendar itself.

// full day names
Calendar._DN = new Array
("Sunnuntai",
 "Maanantai",
 "Tiistai",
 "Keskiviikko",
 "Torstai",
 "Perjantai",
 "Lauantai",
 "Sunnuntai");

// short day names
Calendar._SDN = new Array
("Su",
 "Ma",
 "Ti",
 "Ke",
 "To",
 "Pe",
 "La",
 "Su");

// full month names
Calendar._MN = new Array
("Tammikuu",
 "Helmikuu",
 "Maaliskuu",
 "Huhtikuu",
 "Toukokuu",
 "Kesäkuu",
 "Heinäkuu",
 "Elokuu",
 "Syyskuu",
 "Lokakuu",
 "Marraskuu",
 "Joulukuu");

// short month names
Calendar._SMN = new Array
("Tam",
 "Hel",
 "Maa",
 "Huh",
 "Tou",
 "Kes",
 "Hei",
 "Elo",
 "Syy",
 "Lok",
 "Mar",
 "Jou");

// tooltips
if(Calendar._TT == undefined) Calendar._TT = {};
Calendar._TT["INFO"] = "Tietoja kalenterista";

Calendar._TT["ABOUT"] =
"DHTML Date/Time Selector\n" +
"(c) dynarch.com 2002-2003\n" + // don't translate this this ;-)
"Uusin versio osoitteessa: http://dynarch.com/mishoo/calendar.epl\n" +
"Julkaistu GNU LGPL lisenssin alaisuudessa. Lisätietoja osoitteessa http://gnu.org/licenses/lgpl.html" +
"\n\n" +
"Päivämäärä valinta:\n" +
"- Käytä \xab, \xbb painikkeita valitaksesi vuosi\n" +
"- Käytä " + String.fromCharCode(0x2039) + ", " + String.fromCharCode(0x203a) + " painikkeita valitaksesi kuukausi\n" +
"- Pitämällä hiiren painiketta minkä tahansa yllä olevan painikkeen kohdalla, saat näkyviin valikon nopeampaan siirtymiseen.";
Calendar._TT["ABOUT_TIME"] = "\n\n" +
"Ajan valinta:\n" +
"- Klikkaa kellonajan numeroita lisätäksesi aikaa\n" +
"- tai pitämällä Shift-näppäintä pohjassa saat aikaa taaksepäin\n" +
"- tai klikkaa ja pidä hiiren painike pohjassa sekä liikuta hiirtä muuttaaksesi aikaa nopeasti eteen- ja taaksepäin.";

Calendar._TT["PREV_YEAR"] = "Edell. vuosi (paina hetki, näet valikon)";
Calendar._TT["PREV_MONTH"] = "Edell. kuukausi (paina hetki, näet valikon)";
Calendar._TT["GO_TODAY"] = "Siirry tähän päivään";
Calendar._TT["NEXT_MONTH"] = "Seur. kuukausi (paina hetki, näet valikon)";
Calendar._TT["NEXT_YEAR"] = "Seur. vuosi (paina hetki, näet valikon)";
Calendar._TT["SEL_DATE"] = "Valitse päivämäärä";
Calendar._TT["DRAG_TO_MOVE"] = "Siirrä kalenterin paikkaa";
Calendar._TT["PART_TODAY"] = " (tänään)";
Calendar._TT["MON_FIRST"] = "Näytä maanantai ensimmäisenä";
Calendar._TT["SUN_FIRST"] = "Näytä sunnuntai ensimmäisenä";
Calendar._TT["CLOSE"] = "Sulje";
Calendar._TT["TODAY"] = "Tänään";
Calendar._TT["TIME_PART"] = "(Shift-) Klikkaa tai liikuta muuttaaksesi aikaa";

// date formats
Calendar._TT["DEF_DATE_FORMAT"] = "%d.%m.%Y";
Calendar._TT["TT_DATE_FORMAT"] = "%d.%m.%Y";

Calendar._TT["WK"] = "Vko";
