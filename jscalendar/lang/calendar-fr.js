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

r30416 - 2007-12-12 16:11:11 -0800 (Wed, 12 Dec 2007) - max - Updated french calendar language pack from Synolia.

r550 - 2004-09-03 15:39:17 -0700 (Fri, 03 Sep 2004) - jostrow - Calendar feature


*/

// ** I18N

// For translators: please use UTF-8 if possible.  We strongly believe that
// Unicode is the answer to a real internationalized world.  Also please
// include your contact information in the header, as can be seen above.

// full day names
Calendar._DN = new Array
("Dimanche",
 "Lundi",
 "Mardi",
 "Mercredi",
 "Jeudi",
 "Vendredi",
 "Samedi",
 "Dimanche");

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
("Dim",
 "Lun",
 "Mar",
 "Mer",
 "Jeu",
 "Ven",
 "Sam",
 "Dim");
 
// First day of the week. "0" means display Sunday first, "1" means display
// Monday first, etc.
Calendar._FD = 1;
 
// full month names
Calendar._MN = new Array
("Janvier",
 "Février",
 "Mars",
 "Avril",
 "Mai",
 "Juin",
 "Juillet",
 "Août",
 "Septembre",
 "Octobre",
 "Novembre",
 "Décembre");

// short month names
Calendar._SMN = new Array
("Jan",
 "Fév",
 "Mar",
 "Avr",
 "Mai",
 "Juin",
 "Juil",
 "Août",
 "Sep",
 "Oct",
 "Nov",
 "Déc");

// tooltips
if(Calendar._TT == undefined) Calendar._TT = {};
Calendar._TT["INFO"] = "A propos du calendrier";

Calendar._TT["ABOUT"] =
"DHTML Date/Heure Selecteur\n" +
"(c) dynarch.com 2002-2003\n" + // don't translate this ;-)
"Pour la derniere version visitez: http://dynarch.com/mishoo/calendar.epl\n" +
"Distribue par GNU LGPL.  Voir http://gnu.org/licenses/lgpl.html pour les details." +
"\n\n" +
"Selection de la date :\n" +
"- Utiliser les boutons \xab, \xbb  pour sélectionner l\'année\n" +
"- Utiliser les boutons " + String.fromCharCode(0x2039) + ", " + String.fromCharCode(0x203a) + " pour selectionner les mois\n" +
"- Garder la souris sur n'importe quel bouton pour une sélection plus rapide";
Calendar._TT["ABOUT_TIME"] = "\n\n" +
"Sélection de l\'heure:\n" +
"- Cliquer sur heures ou minutes pour incrémenter\n" +
"- ou Maj-clic pour décrémenter\n" +
"- ou clic et glisser déplacer pour une sélection plus rapide";

Calendar._TT["PREV_YEAR"] = "Année préc. (maintenir pour menu)";
Calendar._TT["PREV_MONTH"] = "Mois préc. (maintenir pour menu)";
Calendar._TT["GO_TODAY"] = "Atteindre date du jour";
Calendar._TT["NEXT_MONTH"] = "Mois suiv. (maintenir pour menu)";
Calendar._TT["NEXT_YEAR"] = "Année suiv. (maintenir pour menu)";
Calendar._TT["SEL_DATE"] = "Choisir une date";
Calendar._TT["DRAG_TO_MOVE"] = "Déplacer";
Calendar._TT["PART_TODAY"] = " (Aujourd'hui)";

// the following is to inform that "%s" is to be the first day of week
// %s will be replaced with the day name.
Calendar._TT["DAY_FIRST"] = "%s en premier";

// This may be locale-dependent.  It specifies the week-end days, as an array
// of comma-separated numbers.  The numbers are from 0 to 6: 0 means Sunday, 1
// means Monday, etc.
Calendar._TT["WEEKEND"] = "0,6";

Calendar._TT["CLOSE"] = "Fermer";
Calendar._TT["TODAY"] = "Aujourd'hui";
Calendar._TT["TIME_PART"] = "(Shift-)Click ou deplacer pour changer la valeur";

// date formats
Calendar._TT["DEF_DATE_FORMAT"] = "%d-%m-%y";
Calendar._TT["TT_DATE_FORMAT"] = " %A %e %B %Y";

Calendar._TT["WK"] = "sem";
Calendar._TT["TIME"] = "Heure:";
