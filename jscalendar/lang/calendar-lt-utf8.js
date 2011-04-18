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

// Calendar LT language
// Author: Martynas Majeris, <martynas@solmetra.lt>
// Encoding: UTF-8
// Distributed under the same terms as the calendar itself.

// For translators: please use UTF-8 if possible.  We strongly believe that
// Unicode is the answer to a real internationalized world.  Also please
// include your contact information in the header, as can be seen above.

// full day names
Calendar._DN = new Array
("Sekmadienis",
 "Pirmadienis",
 "Antradienis",
 "Trečiadienis",
 "Ketvirtadienis",
 "Pentadienis",
 "Šeštadienis",
 "Sekmadienis");

// Please note that the following array of short day names (and the same goes
// for short month names, _SMN) isn't absolutely necessary.  We give it here
// for exemplification on how one can customize the short day names, but if
// they are simply the first N letters of the full name you can simply say:
//
//   Calendar._SDN_len = N; // short day name length
//   Calendar._SMN_len = N; // short month name length
//
// If N = 3 then this is not needed either since we assume a value of 3 if not
// present, to be compatible with translation files that were written before
// this feature.

// short day names
Calendar._SDN = new Array
("Sek",
 "Pir",
 "Ant",
 "Tre",
 "Ket",
 "Pen",
 "Šeš",
 "Sek");

// full month names
Calendar._MN = new Array
("Sausis",
 "Vasaris",
 "Kovas",
 "Balandis",
 "Gegužė",
 "Birželis",
 "Liepa",
 "Rugpjūtis",
 "Rugsėjis",
 "Spalis",
 "Lapkritis",
 "Gruodis");

// short month names
Calendar._SMN = new Array
("Sau",
 "Vas",
 "Kov",
 "Bal",
 "Geg",
 "Bir",
 "Lie",
 "Rgp",
 "Rgs",
 "Spa",
 "Lap",
 "Gru");

// tooltips
if(Calendar._TT == undefined) Calendar._TT = {};
Calendar._TT["INFO"] = "Apie kalendorių";

Calendar._TT["ABOUT"] =
"DHTML Date/Time Selector\n" +
"(c) dynarch.com 2002-2003\n" + // don't translate this this ;-)
"Naujausią versiją rasite: http://dynarch.com/mishoo/calendar.epl\n" +
"Platinamas pagal GNU LGPL licenciją. Aplankykite http://gnu.org/licenses/lgpl.html" +
"\n\n" +
"Datos pasirinkimas:\n" +
"- Metų pasirinkimas: \xab, \xbb\n" +
"- Mėnesio pasirinkimas: " + String.fromCharCode(0x2039) + ", " + String.fromCharCode(0x203a) + "\n" +
"- Nuspauskite ir laikykite pelės klavišą greitesniam pasirinkimui.";
Calendar._TT["ABOUT_TIME"] = "\n\n" +
"Laiko pasirinkimas:\n" +
"- Spustelkite ant valandų arba minučių - skaičius padidės vienetu.\n" +
"- Jei spausite kartu su Shift, skaičius sumažės.\n" +
"- Greitam pasirinkimui spustelkite ir pajudinkite pelę.";

Calendar._TT["PREV_YEAR"] = "Ankstesni metai (laikykite, jei norite meniu)";
Calendar._TT["PREV_MONTH"] = "Ankstesnis mėnuo (laikykite, jei norite meniu)";
Calendar._TT["GO_TODAY"] = "Pasirinkti šiandieną";
Calendar._TT["NEXT_MONTH"] = "Kitas mėnuo (laikykite, jei norite meniu)";
Calendar._TT["NEXT_YEAR"] = "Kiti metai (laikykite, jei norite meniu)";
Calendar._TT["SEL_DATE"] = "Pasirinkite datą";
Calendar._TT["DRAG_TO_MOVE"] = "Tempkite";
Calendar._TT["PART_TODAY"] = " (šiandien)";
Calendar._TT["MON_FIRST"] = "Pirma savaitės diena - pirmadienis";
Calendar._TT["SUN_FIRST"] = "Pirma savaitės diena - sekmadienis";
Calendar._TT["CLOSE"] = "Uždaryti";
Calendar._TT["TODAY"] = "Šiandien";
Calendar._TT["TIME_PART"] = "Spustelkite arba tempkite jei norite pakeisti";

// date formats
Calendar._TT["DEF_DATE_FORMAT"] = "%Y-%m-%d";
Calendar._TT["TT_DATE_FORMAT"] = "%A, %Y-%m-%d";

Calendar._TT["WK"] = "sav";
