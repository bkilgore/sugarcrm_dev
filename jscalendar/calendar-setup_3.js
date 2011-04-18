/*
 
 Modification information for LGPL compliance
 
 r56990 - 2010-06-16 13:05:36 -0700 (Wed, 16 Jun 2010) - kjing - snapshot "Mango" svn branch to a new one for GitHub sync
 
 r56989 - 2010-06-16 13:01:33 -0700 (Wed, 16 Jun 2010) - kjing - defunt "Mango" svn dev branch before github cutover
 
 r55980 - 2010-04-19 13:31:28 -0700 (Mon, 19 Apr 2010) - kjing - create Mango (6.1) based on windex
 
 r51719 - 2009-10-22 10:18:00 -0700 (Thu, 22 Oct 2009) - mitani - Converted to Build 3  tags and updated the build system
 
 r51634 - 2009-10-19 13:32:22 -0700 (Mon, 19 Oct 2009) - mitani - Windex is the branch for Sugar Sales 1.0 development
 
 r50375 - 2009-08-24 18:07:43 -0700 (Mon, 24 Aug 2009) - dwong - branch kobe2 from tokyo r50372
 
 r42807 - 2008-12-29 11:16:59 -0800 (Mon, 29 Dec 2008) - dwong - Branch from trunk/sugarcrm r42806 to branches/tokyo/sugarcrm
 
 r27708 - 2007-10-05 15:45:31 -0700 (Fri, 05 Oct 2007) - julian - Fix for bug #13416: jscalendar does not honor 'First Day of Week' value in I18N files (CONTRIBUTED CODE)
 
 r25053 - 2007-08-03 09:24:58 -0700 (Fri, 03 Aug 2007) - clee - Fix to accomodate for ifFormat parameter that is used by modules when calling Calendar.setup code in Javascript.
 
 r24533 - 2007-07-23 01:35:36 -0700 (Mon, 23 Jul 2007) - clee - Added fixes for IE javascript errors where calendar selection causes other form fields to be disabled/readonly.
 
 
 */
Calendar.setup=function(params){function param_default(pname,def){if(typeof params[pname]=="undefined"){params[pname]=def;}};param_default("inputFieldObj",null);param_default("displayAreaObj",null);param_default("buttonObj",null);param_default("inputField",null);param_default("displayArea",null);param_default("button",null);param_default("eventName","click");param_default("ifFormat","%Y/%m/%d");param_default("daFormat","%Y/%m/%d");param_default("singleClick",true);param_default("disableFunc",null);param_default("dateStatusFunc",params["disableFunc"]);param_default("firstDay",isNaN(Calendar._FD)?0:Calendar._FD);param_default("align","Br");param_default("range",[1900,2999]);param_default("weekNumbers",true);param_default("flat",null);param_default("flatCallback",null);param_default("onSelect",null);param_default("onClose",null);param_default("onOpen",null);param_default("onUpdate",null);param_default("date",null);param_default("showsTime",false);param_default("timeFormat","24");param_default("electric",true);param_default("step",2);param_default("position",null);param_default("cache",false);param_default("showOthers",false);var tmp=["inputField","displayArea","button"];for(var i in tmp)
{if(params[tmp[i]+'Obj']==null&&typeof params[tmp[i]]=="string")
{params[tmp[i]]=document.getElementById(params[tmp[i]]);}
else
{params[tmp[i]]=params[tmp[i]+'Obj'];}}
if(!(params.flat||params.inputField||params.displayArea||params.button)){return false;}
function onSelect(cal){var p=cal.params;var update=(cal.dateClicked||p.electric);if(update&&p.flat){if(typeof p.flatCallback=="function")
p.flatCallback(cal);else
alert("No flatCallback given -- doing nothing.");return false;}
if(update&&p.inputField){val=cal.date.print(p.daFormat);val=val.substring(0,10);p.inputField.value=val;if(typeof p.inputField.onchange=="function")
p.inputField.onchange();}
if(update&&p.displayArea)
p.displayArea.innerHTML=cal.date.print(p.daFormat);if(update&&p.singleClick&&cal.dateClicked)
cal.callCloseHandler();if(update&&typeof p.onUpdate=="function")
p.onUpdate(cal);};if(params.flat!=null){if(typeof params.flat=="string")
params.flat=document.getElementById(params.flat);if(!params.flat){alert("Calendar.setup:\n  Flat specified but can't find parent.");return false;}
var cal=new Calendar(params.firstDay,params.date,params.onSelect||onSelect);cal.showsTime=params.showsTime;cal.time24=(params.timeFormat=="24");cal.params=params;cal.weekNumbers=params.weekNumbers;cal.setRange(params.range[0],params.range[1]);cal.setDateStatusHandler(params.dateStatusFunc);cal.create(params.flat);cal.show();return false;}
var triggerEl=params.button||params.displayArea||params.inputField;triggerEl["on"+params.eventName]=function(){if(params.onOpen){params.onOpen();}
var dateEl=params.inputField||params.displayArea;var dateFmt=((typeof params.ifFormat!="undefined")&&params.ifFormat!="%Y/%m/%d")?params.ifFormat:params.daFormat;params.daFormat=dateFmt;if(dateFmt.indexOf(" ")>-1){dateFmt=dateFmt.substring(0,dateFmt.indexOf(" "));}
var mustCreate=false;var cal=window.calendar;if(!(cal&&params.cache)){window.calendar=cal=new Calendar(params.firstDay,params.date,params.onSelect||onSelect,params.onClose||function(cal){cal.hide();},params.inputField);cal.showsTime=params.showsTime;cal.time24=(params.timeFormat=="24");cal.weekNumbers=params.weekNumbers;mustCreate=true;}else{if(params.date)
cal.setDate(params.date);cal.hide();}
cal.showsOtherMonths=params.showOthers;cal.yearStep=params.step;cal.setRange(params.range[0],params.range[1]);cal.params=params;cal.setDateStatusHandler(params.dateStatusFunc);cal.setDateFormat(dateFmt);if(mustCreate)
cal.create();cal.parseDate(dateEl.value||dateEl.innerHTML);cal.refresh();if(!params.position)
cal.showAtElement(params.button||params.displayArea||params.inputField,params.align);else
cal.showAt(params.position[0],params.position[1]);return false;};};