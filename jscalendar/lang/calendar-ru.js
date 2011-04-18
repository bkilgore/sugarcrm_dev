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
("Воскресенье",
 "Понедельник",
 "Вторник",
 "Среда",
 "Четверг",
 "Пятница",
 "Суббота",
 "Воскресенье");
Calendar._SDN = new Array
("Вс",
 "Пн",
 "Вт",
 "Ср",
 "Чт",
 "Пт",
 "Сб",
 "Вс");
Calendar._SDN_len = 2;

Calendar._MN = new Array
("Январь",
 "Февраль",
 "Март",
 "Апрель",
 "Май",
 "Июнь",
 "Июль",
 "Август",
 "Сентябрь",
 "Октябрь",
 "Ноябрь",
 "Декабрь");
Calendar._SMN = new Array
("Янв",
 "Фев",
 "Мар",
 "Апр",
 "Мая",
 "Июн",
 "Июл",
 "Авг",
 "Сен",
 "Окт",
 "Ноя",
 "Дек");

// tooltips
if(Calendar._TT == undefined) if(Calendar._TT == undefined) Calendar._TT = {};
Calendar._TT["TOGGLE"] = "Сменить день начала недели (ПН/ВС)";
Calendar._TT["PREV_YEAR"] = "Пред. год (удерживать для меню)";
Calendar._TT["PREV_MONTH"] = "Пред. месяц (удерживать для меню)";
Calendar._TT["GO_TODAY"] = "На сегодня";
Calendar._TT["NEXT_MONTH"] = "След. месяц (удерживать для меню)";
Calendar._TT["NEXT_YEAR"] = "След. год (удерживать для меню)";
Calendar._TT["SEL_DATE"] = "Выбрать дату";
Calendar._TT["DRAG_TO_MOVE"] = "Перетащить";
Calendar._TT["PART_TODAY"] = " (сегодня)";
Calendar._TT["MON_FIRST"] = "Показать понедельник первым";
Calendar._TT["SUN_FIRST"] = "Показать воскресенье первым";
Calendar._TT["DAY_FIRST"] = "Показать %s первым";
Calendar._TT["CLOSE"] = "Закрыть";
Calendar._TT["TODAY"] = "Сегодня";

// date formats
Calendar._TT["DEF_DATE_FORMAT"] = "%Y-%m-%d";
Calendar._TT["TT_DATE_FORMAT"] = "%a, %e %b";

Calendar._TT["WK"] = "нед";
