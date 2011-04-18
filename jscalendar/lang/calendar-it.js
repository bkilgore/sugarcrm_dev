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

r48025 - 2009-06-03 21:56:58 -0700 (Wed, 03 Jun 2009) - weidong - 27345, change the langfiles to be utf-8 no BOM

r42807 - 2008-12-29 11:16:59 -0800 (Mon, 29 Dec 2008) - dwong - Branch from trunk/sugarcrm r42806 to branches/tokyo/sugarcrm

r550 - 2004-09-03 15:39:17 -0700 (Fri, 03 Sep 2004) - jostrow - Calendar feature


*/

// ** I18N
Calendar._DN = new Array
("Domenica",
 "Lunedì",
 "Martedì",
 "Mercoledì",
 "Giovedì",
 "Venerdì",
 "Sabato",
 "Domenica");
Calendar._MN = new Array
("Gennaio",
 "Febbraio",
 "Marzo",
 "Aprile",
 "Maggio",
 "Giugno",
 "Luglio",
 "Agosto",
 "Settembre",
 "Ottobre",
 "Novembre",
 "Dicembre");

// short month names
Calendar._SMN = new Array
("Gen",
 "Feb",
 "Mar",
 "Apr",
 "Mag",
 "Giu",
 "Lug",
 "Ago",
 "Set",
 "Ott",
 "Nov",
 "Dic");

// tooltips
if(Calendar._TT == undefined) Calendar._TT = {};
Calendar._TT["INFO"] = "a proposito del calendario";

Calendar._TT["ABOUT"] =
"DHTML Date/Time Selector\n" +
"(c) dynarch.com 2002-2003\n" + // don't translate this this ;-)
"Per le ultime versioni vai a: http://dynarch.com/mishoo/calendar.epl\n" +
"Distribuito su licenza GNU LGPL.  Vedi http://gnu.org/licenses/lgpl.html per i dettagli." +
"\n\n" +
"selezione della data:\n" +
"- Usa i bottoni \xab, \xbb per selezionare l'anno\n" +
"- Usa i bottoni " + String.fromCharCode(0x2039) + ", " + String.fromCharCode(0x203a) + " per selezionare il mese\n" +
"- Utilizza il mouse per una selezione rapida.";
Calendar._TT["ABOUT_TIME"] = "\n\n" +
"selezione dell'ora:\n" +
"- Clicca sull'ora visualizzata per aumentarla\n" +
"- o Shift-click per diminuirla\n" +
"- o click a trascina per la selezione rapida.";


Calendar._TT["TOGGLE"] = "Modifica il primo giorno della settimana";
Calendar._TT["PREV_YEAR"] = "Anno prec. (tieni premuto per menu)";
Calendar._TT["PREV_MONTH"] = "Mese prec. (tieni premuto per menu)";
Calendar._TT["GO_TODAY"] = "Vai a oggi";
Calendar._TT["NEXT_MONTH"] = "Mese succ. (tieni premuto per menu)";
Calendar._TT["NEXT_YEAR"] = "Anno succ. (tieni premuto per menu)";
Calendar._TT["SEL_DATE"] = "Seleziona data";
Calendar._TT["DRAG_TO_MOVE"] = "Trascina per spostare";
Calendar._TT["PART_TODAY"] = " (oggi)";
Calendar._TT["MON_FIRST"] = "Parti da lunedì";
Calendar._TT["SUN_FIRST"] = "Parti da domenica";
Calendar._TT["CLOSE"] = "Chiudi";
Calendar._TT["TODAY"] = "Oggi";

// date formats
Calendar._TT["DEF_DATE_FORMAT"] = "%d-%m-%Y";
Calendar._TT["TT_DATE_FORMAT"] = "%a, %e %b ";

Calendar._TT["WK"] = "Setti";
