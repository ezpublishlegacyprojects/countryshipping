{let shipping_values=$attribute.content
     country_array=ezini('CountrySettings', 'Countries', 'content.ini')
     currency_list=fetch('shop', 'currency_list', hash('status', 'active'))
     preferred_currency_code=$shipping_values.default_currency.code
     pricegroup_hash=fetch('ezshipping', 'pricegroup_identifier_hash')
     additional_country_list=$shipping_values.shipping_country_data
     additional_country_default=$shipping_values.shipping_country_default
     existing_currencies=$shipping_values.shipping_default_data
     existing_currency_counter=0
     first_value_id=0
     add_value_id=0
     base='ContentObjectAttribute'
     shipping_group_list=fetch('ezshipping', 'shipping_group_list')
     shipping_group_id=$attribute.data_int}

<div class="block">
 <div class="element">
  <label>{"Shipping group:"|i18n('countryshipping/design/standard/content/datatype/edit/ezshipping')}</label>
  <select name="{$base}_ezshipping_shipping_group_{$attribute.id}">
  <option value="-1">{"Custom"|i18n('countryshipping/design/standard/content/datatype/edit/ezshipping')}</option>
  {foreach $shipping_group_list as $shipping_group}
  <option value="{$shipping_group.id}"{if $attribute.data_int|eq($shipping_group.id)} selected="selected"{/if}>{$shipping_group.name|wash(xhtml)}</option>
  {/foreach}
  </select>
</div>
<div class="element">
  <label>{"Default currency:"|i18n('countryshipping/design/standard/content/datatype/edit/ezshipping')}</label>
  <select name="{$base}_ezshipping_default_currency_{$attribute.id}" {if $shipping_group_id|gt(0)} disabled="disabled"{/if}>
  {foreach $currency_list as $currency}
  {if is_set($existing_currencies[$currency.code][$pricegroup_hash['first_value']]['value'])}<option value="{$currency.code}"{if $shipping_values.default_currency.code|eq($currency.code)} selected="selected"{/if}>{$currency.code|wash(xhtml)}</option>{/if}
  {/foreach}
  </select>
</div>
<div class="break"></div>
</div>
<input class="button" type="submit" value="{"Update"|i18n('countryshipping/design/standard/content/datatype/edit/ezshipping')}" name="CustomActionButton[{$attribute.id}_update_shipping_group]" />

<p>{"%bold_startFirst value:%bold_end The value that are added for the first item to a product.</p>"|i18n('countryshipping/design/standard/content/datatype/edit/ezshipping', '', hash('%bold_start', '<b>', '%bold_end', '</b>'))}
<p>{"%bold_startAdditional value:%bold_end The value that are added for the second and all other items of the same product."|i18n('countryshipping/design/standard/content/datatype/edit/ezshipping', '', hash('%bold_start', '<b>', '%bold_end', '</b>'))}</p>

<div class="block">
  <label>{"Default shipping:"|i18n('countryshipping/design/standard/content/datatype/edit/ezshipping')}</label>
  <table class="list" border="0">
  <tr>
  <th width="1%">&nbsp;</th>
  <th width="9%">{"Currency"|i18n('countryshipping/design/standard/content/datatype/edit/ezshipping')}</th>
  <th width="45%" colspan="2">{"First value"|i18n('countryshipping/design/standard/content/datatype/edit/ezshipping')}</th>
  <th width="25%" colspan="2">{"Additional value"|i18n('countryshipping/design/standard/content/datatype/edit/ezshipping')}</th>
  <th width="20%">{"Auto rate"|i18n('countryshipping/design/standard/content/datatype/edit/ezshipping')}</th>
  </tr>
  {foreach $currency_list as $code => $currency_object
           sequence array('bglight', 'bgdark') as $bg_value}
  {let $preferred=$code|eq($preferred_currency_code)
       $first_value=$existing_currencies[$code][$pricegroup_hash['first_value']]['value']
       $add_value=$existing_currencies[$code][$pricegroup_hash['additional_value']]['value']}

      {* Values to control the list and selectlist below *}
      {if and(is_set($existing_currencies[$code][$pricegroup_hash['first_value']]['value']),
              is_set($existing_currencies[$code][$pricegroup_hash['additional_value']]['value']))}
         {set existing_currency_counter=$existing_currency_counter|inc}
      {/if}
      {if is_set($existing_currencies[$code][$pricegroup_hash['first_value']])}
         {set first_value_id=concat($code, '_', $pricegroup_hash['first_value'])}
      {else}
         {set first_value_id=0}
      {/if}
      {if is_set($existing_currencies[$code][$pricegroup_hash['additional_value']])}
         {set add_value_id=concat($code, '_', $pricegroup_hash['additional_value'])}
      {else}
         {set add_value_id=0}
      {/if}

      {if is_set($existing_currencies[$code][$pricegroup_hash['first_value']]['auto_value'])}
         {set first_value=div(round(mul($existing_currencies[$preferred_currency_code][$pricegroup_hash['first_value']]['value']|div($currency_list[$preferred_currency_code].rate_value)|mul($currency_list[$code].rate_value),100)),100)}
      {/if}
      {if is_set($existing_currencies[$code][$pricegroup_hash['additional_value']]['auto_value'])}
         {set add_value=$existing_currencies[$code][$pricegroup_hash['additional_value']]['auto_value']}
      {/if}
   <tr class="{$bg_value}">
   <td width="1%"><input type="checkbox" name="{$base}_ezshipping_selected_currency_array_{$attribute.id}[]" value="{$code}" {if or($shipping_group_id|gt(0), is_unset($existing_currencies[$code][$pricegroup_hash['first_value']]['value']), is_unset($existing_currencies[$code][$pricegroup_hash['additional_value']]['value']), $preferred|eq(true()))}disabled="disabled" {/if}/></td>
   <td>{$code|wash(xhtml)}</td>
   <td width="1%" style="text-align: right;">{$currency_list[$code].symbol|wash(xhtml)}</td>
   <td>{if is_unset($existing_currencies[$code][$pricegroup_hash['first_value']]['value'])}{$first_value|wash(xhtml)} {"(Auto)"|i18n('countryshipping/design/standard/content/datatype/edit/ezshipping')}{else}<input type="text" name="{$base}_ezshipping_currency_value_{$first_value_id}_{$attribute.id}" value="{$first_value}" size="4"{if $shipping_group_id|gt(0)} disabled="disabled"{/if} />{/if}</td>
   <td width="1%" style="text-align: right;">{$currency_list[$code].symbol|wash(xhtml)}</td>
   <td>{if is_unset($existing_currencies[$code][$pricegroup_hash['additional_value']]['value'])}{$add_value|wash(xhtml)} {"(Auto)"|i18n('countryshipping/design/standard/content/datatype/edit/ezshipping')}{else}<input type="text" name="{$base}_ezshipping_currency_value_{$add_value_id}_{$attribute.id}" value="{$add_value|wash(xhtml)}" size="4"{if $shipping_group_id|gt(0)}disabled="disabled"{/if} />{/if}</td>
   <td>{$currency_object.rate_value}</td>
   </tr>
   {/let}
   {/foreach}
   </table>
   </div>

   
     <input class="button" type="submit" name="CustomActionButton[{$attribute.id}_remove_selected_currency]" value="{"Remove selected currencies"|i18n('countryshipping/design/standard/content/datatype/edit/ezshipping')}" title="{"Remove currency."|i18n('countryshipping/design/standard/content/datatype/edit/ezshipping')}" {if $shipping_group_id|gt(0)}disabled="disabled"{/if} />
       {let select_counter=0}
       &nbsp;<select name="{$base}_ezshipping_new_currency_{$attribute.id}"{if or($currency_list|count|le($existing_currency_counter), $shipping_group_id|gt(0))} disabled="disabled"{/if}>
       {foreach $currency_list as $code => $currency_object}
         {if and(is_unset($existing_currencies[$code][$pricegroup_hash['first_value']]['value']),
             and(is_unset($existing_currencies[$code][$pricegroup_hash['additional_value']]['value'])))}<option value="{$code}">{$code|wash(xhtml)}</option>{set select_counter=$select_counter|inc}{/if}
       {/foreach}
       {if $select_counter|eq(0)}
       <option value="">{"[Emty]"|i18n('countryshipping/design/standard/content/datatype/edit/ezshipping')}</option>
       {/if}
       </select>
       {/let}
       <input class="button" type="submit" name="CustomActionButton[{$attribute.id}_add_currency]" value="{"Add currency"|i18n('countryshipping/design/standard/content/datatype/edit/ezshipping')}" title="{"Add currency."|i18n('countryshipping/design/standard/content/datatype/edit/ezshipping')}" {if or($currency_list|count|le($existing_currency_counter), $shipping_group_id|gt(0))}disabled="disabled"{/if} />

   <div class="block">
     <label>{"Additional country shipping (first / additional):"|i18n('countryshipping/design/standard/content/datatype/edit/ezshipping')}</label>
     <table class="list" border="0">
     <tr>
     <th width="1%">&nbsp;</th>
     <th>{"Country"|i18n('countryshipping/design/standard/content/datatype/edit/ezshipping')}</th>
     {foreach $currency_list as $code => $currency_object}
     <th>{"Values (%code)"|i18n('countryshipping/design/standard/content/datatype/edit/ezshipping', '', hash('%code', $code|wash(xhtml)))}</th>
     {/foreach}
     </tr>
     <tr class="bglight">
     <td>&nbsp;</td>
     <td>{"Other countries"|i18n('countryshipping/design/standard/content/datatype/edit/ezshipping')}</td>

     {foreach $currency_list as $code => $currency_object}
     {let $first_value=$additional_country_default[$code][$pricegroup_hash['first_value']]['value']
          $add_value=$additional_country_default[$code][$pricegroup_hash['additional_value']]['value']}

      {set first_value_id=concat($code, '_', $pricegroup_hash['first_value'])}
      {set add_value_id=concat($code,'_',$pricegroup_hash['additional_value'])}

      {if is_unset($additional_country_default[$code][$pricegroup_hash['first_value']]['value'])}
         {set first_value=$additional_country_default[$code][$pricegroup_hash['first_value']]['auto_value']}
      {/if}
      {if is_unset($additional_country_default[$code][$pricegroup_hash['first_value']]['value'])}
         {set add_value=$additional_country_default[$code][$pricegroup_hash['additional_value']]['auto_value']}
      {/if}
      <td>{if is_unset($existing_currencies[$code][$pricegroup_hash['first_value']]['value'])}{$currency_object.symbol|wash(xhtml)}&nbsp;{$first_value|wash(xhtml)} / {$add_value|wash(xhtml)} {"(Auto)"|i18n('countryshipping/design/standard/content/datatype/edit/ezshipping')}{else}{$currency_object.symbol|wash(xhtml)}&nbsp;<input type="text" value="{$first_value|wash(xhtml)}" size="4" name="{$base}_ezshipping_country_default_value_{$first_value_id}_{$attribute.id}"{if $shipping_group_id|gt(0)}disabled="disabled"{/if} /> / <input type="text" value="{$add_value|wash(xhtml)}" size="4" name="{$base}_ezshipping_country_default_value_{$add_value_id}_{$attribute.id}"{if $shipping_group_id|gt(0)} disabled="disabled" {/if}/>{/if}</td>
      {/let}
      {/foreach}
      </tr>
    {foreach $additional_country_list as $country_name => $additional_country
             sequence array('bgdark', 'bglight') as $bg_value}
      <tr class="{$bg_value}">
      <td><input type="checkbox" name="{$base}_ezshipping_selected_country_array_{$attribute.id}[]" value="{$country_name|wash(xhtml)}"{if $shipping_group_id|gt(0)} disabled="disabled="{/if} /></td>
      <td>{$country_name|wash(xhtml)}</td>
      {foreach $currency_list as $code => $currency_object}
      {let $preferred=$code|eq($preferred_currency_code)
           $first_value=$additional_country[$code][$pricegroup_hash['first_value']]['value']
           $add_value=$additional_country[$code][$pricegroup_hash['additional_value']]['value']}

      {if is_set($additional_country[$code][$pricegroup_hash['first_value']]['value'])}
         {set first_value_id=concat($code, '_', $pricegroup_hash['first_value'])}
      {else}
         {set first_value_id=0}
      {/if}
      {if is_set($additional_country[$code][$pricegroup_hash['additional_value']]['value'])}
         {set add_value_id=concat($code, '_', $pricegroup_hash['additional_value'])}
      {else}
         {set add_value_id=0}
      {/if}

      {if is_unset($additional_country[$code][$pricegroup_hash['first_value']]['value'])}
         {set first_value=$additional_country[$code][$pricegroup_hash['first_value']]['auto_value']}
      {/if}
      {if is_unset($additional_country[$code][$pricegroup_hash['additional_value']]['value'])}
         {set add_value=$additional_country[$code][$pricegroup_hash['additional_value']]['auto_value']}
      {/if}
      <td>{if is_unset($existing_currencies[$code][$pricegroup_hash['first_value']]['value'])}{$currency_object.symbol|wash(xhtml)}&nbsp;{$first_value|wash(xhtml)} / {$add_value|wash(xhtml)} {"(Auto)"|i18n('countryshipping/design/standard/content/datatype/edit/ezshipping')}{else}{$currency_object.symbol|wash(xhtml)}&nbsp;<input type="text" value="{$first_value|wash(xhtml)}" size="4" name="{$base}_ezshipping_country_value_{$first_value_id}_{$attribute.id}[{$country_name}]"{if $shipping_group_id|gt(0)} disabled="disabled"{/if}/> / <input type="text" value="{$add_value}" size="4" name="{$base}_ezshipping_country_value_{$add_value_id}_{$attribute.id}[{$country_name}]"{if $shipping_group_id|gt(0)} disabled="disabled"{/if} />{/if}</td>
      {/let}
      {/foreach}
      </tr>
    {/foreach}
      </table>
      </div>

      <div class="block"><input class="button" type="submit" name="CustomActionButton[{$attribute.id}_remove_selected_countries]" value="{"Remove selected countries"|i18n('countryshipping/design/standard/content/datatype/edit/ezshipping')}" title="{"Remove the selected countries."|i18n('countryshipping/design/standard/content/datatype/edit/ezshipping')}" {if $shipping_group_id|gt(0)}disabled="disabled" {/if}/>
      </div>

  <div class="block">
    <label>{"Add additional countries:"|i18n('countryshipping/design/standard/content/datatype/edit/ezshipping')}</label>
    <select name="{$base}_ezshipping_additional_country_array_{$attribute.id}[]" size="5" multiple="multiple" {if $shipping_group_id|gt(0)}disabled="disabled"{/if}>
    {foreach $country_array as $country}
    <option value="{$country}">{$country|wash(xhtml)}</option>
    {/foreach}
    </select>
  </div>
  <div class="block">
    <input class="button" type="submit" value="{"Add countries"|i18n('countryshipping/design/standard/content/datatype/edit/ezshipping')}" name="CustomActionButton[{$attribute.id}_add_countries]"{if $shipping_group_id|gt(0)} disabled="disabled"{/if} />
  </div>
{/let}