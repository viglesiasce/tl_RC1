{* 
Testlink Open Source Project - http://testlink.sourceforge.net/
$Id: inc_ext_js.tpl,v 1.14 2010/09/27 20:01:02 franciscom Exp $
Purpose: include files for:
         Ext JS Library - Copyright(c) 2006-2007, Ext JS, LLC.
         licensing@extjs.com - http://www.extjs.com/license


rev :
     20100927 - franciscom - added new ext-js extension TableGrid.js
     20100621 - eloff - BUGID 3523 - refactor to remove smarty deprecated {php}
                                     use guard_header_smarty() instead
     20100620 - franciscom - reset.css has changed on new extjs distribution to reset-min.css
     20100614 - eloff - BUGID 3523 - prevent loading ext-js more than once
     20090730 - francisco.mancardi@gruppotesi.com
     refactored to use ext-js 3.0
     
     20071008 - franciscom - include prototype.js support
*}

{if guard_header_smarty(__FILE__)}

  {assign var="$css_only" value="$css_only|default:0"}
  {assign var="ext_location" value=$smarty.const.TL_EXTJS_RELATIVE_PATH}
  {if isset($bResetEXTCss) && $bResetEXTCss}
  	<link rel="stylesheet" type="text/css" href="{$basehref}{$ext_location}/css/reset-min.css" />
  {/if}
  <link rel="stylesheet" type="text/css" href="{$basehref}{$ext_location}/css/ext-all.css" />
  
  {if $css_only == 0}
      {*
      not useful
      <script type="text/javascript" src="{$basehref}{$ext_location}/adapter/prototype/prototype.js" language="javascript"></script>
      <script type="text/javascript" src="{$basehref}{$ext_location}/adapter/prototype/ext-prototype-adapter.js" language="javascript"></script>
      *}
      <script type="text/javascript" src="{$basehref}{$ext_location}/adapter/ext/ext-base.js" language="javascript"></script>
      <script type="text/javascript" src="{$basehref}{$ext_location}/ext-all.js" language="javascript"></script>
      
      <script type="text/javascript" src="{$basehref}{$ext_location}/ux/Reorderer.js" language="javascript"></script>
      <script type="text/javascript" src="{$basehref}{$ext_location}/ux/ToolbarReorderer.js" language="javascript"></script>
      <script type="text/javascript" src="{$basehref}{$ext_location}/ux/ToolbarDroppable.js" language="javascript"></script>
      <script type="text/javascript" src="{$basehref}{$ext_location}/ux/Exporter-all.js" language="javascript"></script>
  
      {* 20100927 - franciscom - convert HTML table in ext-js grid *}
      <script type="text/javascript" src="{$basehref}{$ext_location}/ux/TableGrid.js" language="javascript"></script>
  {/if}

{/if}