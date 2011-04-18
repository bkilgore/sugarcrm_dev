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
("Domingo",
 "Lunes",
 "Martes",
 "Miercoles",
 "Jueves",
 "Viernes",
 "Sabado",
 "Domingo");
Calendar._MN = new Array
("Enero",
 "Febrero",
 "Marzo",
 "Abril",
 "Mayo",
 "Junio",
 "Julio",
 "Agosto",
 "Septiembre",
 "Octubre",
 "Noviembre",
 "Diciembre");

// tooltips
if(Calendar._TT == undefined) Calendar._TT = {};
Calendar._TT["INFO"] = "Información del Calendario";

Calendar._TT["ABOUT"] =
"DHTML Date/Time Selector\n" +
"(c) dynarch.com 2002-2003\n" + // don't translate this this ;-)
"Nuevas versiones en: http://dynarch.com/mishoo/calendar.epl\n" +
"Distribuida bajo licencia GNU LGPL.  Para detalles vea http://gnu.org/licenses/lgpl.html ." +
"\n\n" +
"Selección de Fechas:\n" +
"- Use  \xab, \xbb para seleccionar el año\n" +
"- Use " + String.fromCharCode(0x2039) + ", " + String.fromCharCode(0x203a) + " para seleccionar el mes\n" +
"- Mantenga presionado el botón del ratón en cualquiera de las opciones superiores para un acceso rapido .";
Calendar._TT["ABOUT_TIME"] = "\n\n" +
"Selección del Reloj:\n" +
"- Seleccione la hora para cambiar el reloj\n" +
"- o presione  Shift-click para disminuirlo\n" +
"- o presione click y arrastre del ratón para una selección rapida.";

Calendar._TT["TOGGLE"] = "Primer dia de la semana";
Calendar._TT["PREV_YEAR"] = "Año anterior (Presione para menu)";
Calendar._TT["PREV_MONTH"] = "Mes Anterior (Presione para menu)";
Calendar._TT["GO_TODAY"] = "Ir a Hoy";
Calendar._TT["NEXT_MONTH"] = "Mes Siguiente (Presione para menu)";
Calendar._TT["NEXT_YEAR"] = "Año Siguiente (Presione para menu)";
Calendar._TT["SEL_DATE"] = "Seleccione fecha";
Calendar._TT["DRAG_TO_MOVE"] = "Arrastre y mueva";
Calendar._TT["PART_TODAY"] = " (Hoy)";
Calendar._TT["MON_FIRST"] = "Lunes Primero";
Calendar._TT["SUN_FIRST"] = "Domingo Primero";
Calendar._TT["CLOSE"] = "Cerrar";
Calendar._TT["TODAY"] = "Hoy";

// date formats
Calendar._TT["DEF_DATE_FORMAT"] = "dd-mm-yy";
Calendar._TT["TT_DATE_FORMAT"] = "%A, %e de %B de %Y";

Calendar._TT["WK"] = "Smn";
