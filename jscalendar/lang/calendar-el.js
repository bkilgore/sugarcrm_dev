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
Calendar._DN = new Array
("Κυριακή",
 "Δευτέρα",
 "Τρίτη",
 "Τετάρτη",
 "Πέμπτη",
 "Παρασκευή",
 "Σάββατο",
 "Κυριακή");

Calendar._SDN = new Array
("Κυ",
 "Δε",
 "Tρ",
 "Τε",
 "Πε",
 "Πα",
 "Σα",
 "Κυ");

Calendar._MN = new Array
("Ιανουάριος",
 "Φεβρουάριος",
 "Μάρτιος",
 "Απρίλιος",
 "Μάϊος",
 "Ιούνιος",
 "Ιούλιος",
 "Αύγουστος",
 "Σεπτέμβριος",
 "Οκτώβριος",
 "Νοέμβριος",
 "Δεκέμβριος");

Calendar._SMN = new Array
("Ιαν",
 "Φεβ",
 "Μαρ",
 "Απρ",
 "Μαι",
 "Ιουν",
 "Ιουλ",
 "Αυγ",
 "Σεπ",
 "Οκτ",
 "Νοε",
 "Δεκ");

// tooltips
if(Calendar._TT == undefined) Calendar._TT = {};
Calendar._TT["INFO"] = "Για το ημερολόγιο";

Calendar._TT["ABOUT"] =
"Επιλογέας ημερομηνίας/ώρας σε DHTML\n" +
"(c) dynarch.com 2002-2003\n" + // don't translate this this ;-)
"Για τελευταία έκδοση: http://dynarch.com/mishoo/calendar.epl\n" +
"Distributed under GNU LGPL.  See http://gnu.org/licenses/lgpl.html for details." +
"\n\n" +
"Επιλογή ημερομηνίας:\n" +
"- Χρησιμοποιείστε τα κουμπιά \xab, \xbb για επιλογή έτους\n" +
"- Χρησιμοποιείστε τα κουμπιά " + String.fromCharCode(0x2039) + ", " + String.fromCharCode(0x203a) + " για επιλογή μήνα\n" +
"- Κρατήστε κουμπί ποντικού πατημένο στα παραπάνω κουμπιά για πιο γρήγορη επιλογή.";
Calendar._TT["ABOUT_TIME"] = "\n\n" +
"Επιλογή ώρας:\n" +
"- Κάντε κλικ σε ένα από τα μέρη της ώρας για αύξηση\n" +
"- ή Shift-κλικ για μείωση\n" +
"- ή κλικ και μετακίνηση για πιο γρήγορη επιλογή.";
Calendar._TT["TOGGLE"] = "Μπάρα πρώτης ημέρας της εβδομάδας";
Calendar._TT["PREV_YEAR"] = "Προηγ. έτος (κρατήστε για το μενού)";
Calendar._TT["PREV_MONTH"] = "Προηγ. μήνας (κρατήστε για το μενού)";
Calendar._TT["GO_TODAY"] = "Σήμερα";
Calendar._TT["NEXT_MONTH"] = "Επόμενος μήνας (κρατήστε για το μενού)";
Calendar._TT["NEXT_YEAR"] = "Επόμενο έτος (κρατήστε για το μενού)";
Calendar._TT["SEL_DATE"] = "Επιλέξτε ημερομηνία";
Calendar._TT["DRAG_TO_MOVE"] = "Σύρτε για να μετακινήσετε";
Calendar._TT["PART_TODAY"] = " (σήμερα)";
Calendar._TT["MON_FIRST"] = "Εμφάνιση Δευτέρας πρώτα";
Calendar._TT["SUN_FIRST"] = "Εμφάνιση Κυριακής πρώτα";
Calendar._TT["CLOSE"] = "Κλείσιμο";
Calendar._TT["TODAY"] = "Σήμερα";
Calendar._TT["TIME_PART"] = "(Shift-)κλικ ή μετακίνηση για αλλαγή";

// date formats
Calendar._TT["DEF_DATE_FORMAT"] = "dd-mm-y";
Calendar._TT["TT_DATE_FORMAT"] = "%a, %e %b";

Calendar._TT["WK"] = "εβδ";

