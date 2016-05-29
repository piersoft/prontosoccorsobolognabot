<?php  ?>
<!DOCTYPE HTML>
<html>
  <head>
    <title>OpenLayers Demo</title>
    <style type="text/css">
      html, body, #basicMap {
          width: 100%;
          height: 100%;
          margin: 0;
      }
    </style>
    <script src="OpenLayers.js"></script>
<div class="portlet-body"> <script type="text/javascript" src="/PugliaSalutePortlet/resources/magnific-popup/jquery.magnific-popup.min.js"></script> <script type="text/javascript">var _labelTelefono="telefono";var _labelEmail="email";var _labelFax="fax";var _labelOrari="orari";var _currentUri="http://www.sanita.puglia.it/web";var _currentTipo="27501";var _currentTipologia="ps";var _urlList="http://www.sanita.puglia.it/web/pugliasalute/pronto-soccorso-primo-intervento?p_p_id=geo_WAR_PugliaSalutePortlet&p_p_lifecycle=2&p_p_state=normal&p_p_mode=view&p_p_resource_id=list&p_p_cacheability=cacheLevelPage&p_p_col_id=column-1&p_p_col_pos=1&p_p_col_count=2";var _urlRegione="http://www.sanita.puglia.it/web/pugliasalute/pronto-soccorso-primo-intervento?p_p_id=geo_WAR_PugliaSalutePortlet&p_p_lifecycle=2&p_p_state=normal&p_p_mode=view&p_p_resource_id=regione&p_p_cacheability=cacheLevelPage&p_p_col_id=column-1&p_p_col_pos=1&p_p_col_count=2";var _urlAsl="http://www.sanita.puglia.it/web/pugliasalute/pronto-soccorso-primo-intervento?p_p_id=geo_WAR_PugliaSalutePortlet&p_p_lifecycle=2&p_p_state=normal&p_p_mode=view&p_p_resource_id=asl&p_p_cacheability=cacheLevelPage&p_p_col_id=column-1&p_p_col_pos=1&p_p_col_count=2";var _urlDettaglio="pronto-soccorso";var _friendlyUrl="";var _restFileUrl="/PugliaSalutePortlet/rest/journal/getFileUrl";var _labelDetail="Leggi ancora";var _aslList=new Array();$(document).ready(function(){showWaitMessage();_aslList.push("160114");_aslList.push("160113");_aslList.push("160106");_aslList.push("160115");_aslList.push("160116");_aslList.push("160112");$.ajax({url:"/coordinate/Puglia.json",dataType:"json",contentType:"application/json; charset=UTF-8",type:"POST",success:function(a){createMap(a,"27501Map");addElements()},error:function(){hideWaitMessage()}})});</script> <script type="text/javascript">_linkGeoTootltip="Leggi ancora";</script>
  </head>
  <body onload="init();">
    <div id="basicMap"></div>
  </body>
</html>
