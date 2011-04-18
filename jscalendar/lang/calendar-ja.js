/*

Modification information for LGPL compliance

r58364 - 2010-09-29 22:39:05 -0700 (Wed, 29 Sep 2010) - kjing - Author: Stanislav Malyshev <smalyshev@gmail.com>
    Fix languages in JS calendar

r58354 - 2010-09-29 14:39:04 -0700 (Wed, 29 Sep 2010) - kjing - Author: Majed Itani <mitani@mitani.local>
     fixes issues where language pack calendars were not compatible


*/

// ** I18N
Calendar._DN = new Array
("日曜日",
 "月曜日",
 "火曜日",
 "水曜日",
 "木曜日",
 "金曜日",
 "土曜日",
 "日曜日");

// short day names
Calendar._SDN = new Array
("日",
 "月",
 "火",
 "水",
 "木",
 "金",
 "土",
 "日");


Calendar._MN = new Array
("1月",
 "2月",
 "3月",
 "4月",
 "5月",
 "6月",
 "7月",
 "8月",
 "9月",
 "10月",
 "11月",
 "12月");
// short month names
Calendar._SMN = new Array
("1月",
 "2月",
 "3月",
 "4月",
 "5月",
 "6月",
 "7月",
 "8月",
 "9月",
 "10月",
 "11月",
 "12月");

// tooltips

Calendar._TT["INFO"] = "カレンダーについて";

Calendar._TT["ABOUT"] =
"DHTML Date/Time Selector\n" +
"(c) dynarch.com 2002-2003\n" + // don't translate this this ;-)
"For latest version visit: http://dynarch.com/mishoo/calendar.epl\n" +
"Distributed under GNU LGPL.  See http://gnu.org/licenses/lgpl.html for details." +
"\n\n" +
"Date selection:\n" +
"- Use the \xab, \xbb buttons to select year\n" +
"- Use the " + String.fromCharCode(0x2039) + ", " + String.fromCharCode(0x203a) + " buttons to select month\n" +
"- Hold mouse button on any of the above buttons for faster selection.";
Calendar._TT["ABOUT_TIME"] = "\n\n" +
"Time selection:\n" +
"- Click on any of the time parts to increase it\n" +
"- or Shift-click to decrease it\n" +
"- or click and drag for faster selection.";

Calendar._TT["PREV_YEAR"] = "前年";
Calendar._TT["PREV_MONTH"] = "前月";
Calendar._TT["GO_TODAY"] = "今日へ";
Calendar._TT["NEXT_MONTH"] = "翌月";
Calendar._TT["NEXT_YEAR"] = "翌年";
Calendar._TT["SEL_DATE"] = "日付選択";
Calendar._TT["DRAG_TO_MOVE"] = "ウィンドウの移動";
Calendar._TT["PART_TODAY"] = " (今日)";

// the following is to inform that "%s" is to be the first day of week
// %s will be replaced with the day name.
Calendar._TT["DAY_FIRST"] = "%sを最初に表示";

// This may be locale-dependent.  It specifies the week-end days, as an array
// of comma-separated numbers.  The numbers are from 0 to 6: 0 means Sunday, 1
// means Monday, etc.
Calendar._TT["WEEKEND"] = "0,6";

Calendar._TT["CLOSE"] = "閉じる";
Calendar._TT["TODAY"] = "今日";
Calendar._TT["TIME_PART"] = "(Shift-)クリックかドラッグで変更";

// date formats
//Calendar._TT["DEF_DATE_FORMAT"] = "y-mm-dd";
Calendar._TT["DEF_DATE_FORMAT"] = "%Y-%m-%d";
//Calendar._TT["TT_DATE_FORMAT"] = "%a, %b %e";
Calendar._TT["TT_DATE_FORMAT"] = "%b %e日 %a曜日";

Calendar._TT["WK"] = "週";
Calendar._TT["TIME"] = "時間:";
