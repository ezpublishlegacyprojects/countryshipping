{let group_list=fetch('ezshipping', 'shipping_group_list')}
<form action={concat('ezshipping/action/', $group.id)|ezurl()} method="post">
<input type="hidden" name="ActionValue" value="grouplist" />
<div class="context-block">

<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">
  <h1 class="context-title">{"Shipping group list"|i18n('countryshipping/design/standard/ezshipping/grouplist')}</h1>
  <div class="header-mainline"></div>
</div></div></div></div></div></div>

<div class="box-ml"><div class="box-mr"><div class="box-content">
<div class="context-information">

<table class="list" border="0">
<tr>
<th width="1%">&nbsp;</th>
<th>{"Name"|i18n('countryshipping/design/standard/ezshipping/grouplist')}</th>
<th width="1%">&nbsp;</th>
</tr>
{foreach $group_list as $group_item
         sequence array('bgdark','bglight') as $bg_value}
<tr class="{$bg_value}">
  <td><input type="checkbox" name="ShippingGroupArray[]" value="{$group_item.id}" /></td>
  <td>{$group_item.name|wash(xhtml)}</td>
  <td><a href={concat('ezshipping/groupedit/', $group_item.id)|ezurl}><img src={'edit.png'|ezimage()} /></td>
</tr>
{/foreach}
</table>

</div>
</div></div></div>

<div class="controlbar">
<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">
    <div class="block">
    <input class="button" type="submit" name="RemoveShippingGroup" value="{"Remove selected"|i18n('countryshipping/design/standard/ezshipping/grouplist')}" title="{"Remove selected shipping groups."|i18n('countryshipping/design/standard/ezshipping/grouplist')}" />
    <input class="button" type="submit" name="NewShippingGroup" value="{"New shipping group"|i18n('countryshipping/design/standard/ezshipping/grouplist')}" title="{"Create a new shippinggroup."|i18n('countryshipping/design/standard/ezshipping/grouplist')}" />
    </div>
</div></div></div></div></div></div>
</div>

</div>
</form>
{/let}