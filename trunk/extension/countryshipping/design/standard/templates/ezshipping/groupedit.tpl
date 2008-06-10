{let group=fetch('ezshipping', 'shipping_group', hash('shippinggroup_id', $view_parameters.shippinggroup_id))
     shipping_values=$group.group_list
     base='ShippingGroup'
     attribute_id=$group.id}
<form action={concat('ezshipping/action/', $group.id)|ezurl()} method="post">
<div class="context-block">

<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">
  <h1 class="context-title">{"Edit Shipping group"|i18n('countryshipping/design/standard/ezshipping/groupedit')}</h1>
  <div class="header-mainline"></div>
</div></div></div></div></div></div>

<div class="box-ml"><div class="box-mr"><div class="box-content">
<div class="context-information">
  <input type="hidden" name="RedirectURL" value={concat('ezshipping/groupedit/', $group.id)|ezurl()} />
  <input type="hidden" name="ViewMode" value="groupedit" />
  <input type="hidden" name="ActionValue" value="groupedit" />

  <div class="block">
    <label>{"Name:"|i18n('countryshipping/design/standard/ezshipping/groupedit')}</label>
    <input class="box" type="text" name="ShippingGroupName" value="{$group.name|wash(xhtml)}" />
  </div>
  {include uri='design:ezshipping/shippingedit.tpl'
           shipping_values=$group.group_list
           attribute_id=$group.id
           base=$base}
</div>
</div></div></div>

<div class="controlbar">
<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">
    <div class="block">
    <input class="button" type="submit" name="StoreButton"   value="{"Apply"|i18n('countryshipping/design/standard/ezshipping/groupedit')}" title="{"Store changes and exit from edit mode."|i18n('countryshipping/design/standard/ezshipping/groupedit')}" />
    <input class="button" type="submit" name="DiscardButton" value="{"Back"|i18n('countryshipping/design/standard/ezshipping/groupedit')}" title="{"Discard all changes and exit from edit mode."|i18n('countryshipping/design/standard/ezshipping/groupedit')}" />
    </div>
</div></div></div></div></div></div>
</div>

</div>
</form>

{/let}