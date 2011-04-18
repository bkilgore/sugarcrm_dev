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

/* 
	calendar-cs-win.js
	language: Czech
	encoding: utf-8
	author: Lubos Jerabek (xnet@seznam.cz)
*/

// ** I18N
Calendar._DN = new Array('Ned¨§le','Pond¨§l¨ª','¨²tery','St?eda','?tvrtek','P¨¢tek','Sobota','Ned¨§le');
Calendar._SDN = new Array('Ne','Po','¨²t','St','?t','P¨¢','So','Ne');
Calendar._MN = new Array('Leden','¨²nor','B?ezen','Duben','Kv¨§ten','?erven','?ervenec','Srpen','Z¨¢?¨ª','?¨ªjen','Listopad','Prosinec');
Calendar._SMN = new Array('Led','¨²no','B?e','Dub','Kv¨§','?rv','?vc','Srp','Z¨¢?','?¨ªj','Lis','Pro');

// tooltips
if(Calendar._TT == undefined) Calendar._TT = {};
Calendar._TT["TOGGLE"] = "Zm¨§na prvn¨ªho dne v tydnu";
Calendar._TT["PREV_YEAR"] = "P?edchoz¨ª rok (p?idr? pro menu)";
Calendar._TT["PREV_MONTH"] = "P?edchoz¨ª m¨§s¨ªc (p?idr? pro menu)";
Calendar._TT["GO_TODAY"] = "Dne?n¨ª datum";
Calendar._TT["NEXT_MONTH"] = "Dal?¨ª m¨§s¨ªc (p?idr? pro menu)";
Calendar._TT["NEXT_YEAR"] = "Dal?¨ª rok (p?idr? pro menu)";
Calendar._TT["SEL_DATE"] = "Vyber datum";
Calendar._TT["DRAG_TO_MOVE"] = "Chy? a t¨¢hni, pro p?esun";
Calendar._TT["PART_TODAY"] = " (dnes)";
Calendar._TT["MON_FIRST"] = "Uka? jako prvn¨ª Pond¨§l¨ª";
Calendar._TT["SUN_FIRST"] = "Uka? jako prvn¨ª Ned¨§li";
Calendar._TT["CLOSE"] = "Zav?¨ªt";
Calendar._TT["TODAY"] = "Dnes";

// date formats
Calendar._TT["DEF_DATE_FORMAT"] = "d.m.yy";
Calendar._TT["TT_DATE_FORMAT"] = "%A, %e.%B";

Calendar._TT["WK"] = "wk";
