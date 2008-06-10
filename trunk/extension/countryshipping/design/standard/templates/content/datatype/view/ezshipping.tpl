{let shipping_values=$attribute.content
     shipping_group_id=$attribute.data_int
     country_array=ezini('CountrySettings', 'Countries', 'content.ini')
     currency_list=fetch('shop', 'currency_list', hash('status', 'active'))
     preferred_currency_code=$shipping_values.default_currency.code
     pricegroup_hash=fetch('ezshipping', 'pricegroup_identifier_hash')
     additional_country_list=$shipping_values.shipping_country_data
     additional_country_default=$shipping_values.shipping_country_default
     existing_currencies=$shipping_values.shipping_default_data
     existing_currency_counter=0
     first_value_id=0
     add_value_id=0}
  <div class="block">
      <label>{"Default shipping:"|i18n('countryshipping/design/standard/content/datatype/view/ezshipping')}</label>
      <table class="list" border="0">
      <tr>
      <th width="9%">{"Currency"|i18n('countryshipping/design/standard/content/datatype/view/ezshipping')}</th>
      <th width="45%" colspan="2">{"Value first"|i18n('countryshipping/design/standard/content/datatype/view/ezshipping')}</th>
      <th width="25%" colspan="2">{"Value additional"|i18n('countryshipping/design/standard/content/datatype/view/ezshipping')}</th>
      <th width="20%">{"Auto rate"|i18n('countryshipping/design/standard/content/datatype/view/ezshipping')}</th>
      </tr>
      {foreach $currency_list as $code => $currency_object
               sequence array('bglight', 'bgdark') as $bg_value}
      {let $preferred=$code|eq($preferred_currency_code)
           $first_value=$existing_currencies[$code][$pricegroup_hash['first_value']]['value']
           $add_value=$existing_currencies[$code][$pricegroup_hash['additional_value']]['value']}

      {if is_set($existing_currencies[$code][$pricegroup_hash['first_value']]['auto_value'])}
         {set first_value=concat($existing_currencies[$code][$pricegroup_hash['first_value']]['auto_value'], ' (Auto)')}
      {/if}
      {if is_set($existing_currencies[$code][$pricegroup_hash['additional_value']]['auto_value'])}
         {set add_value=concat($existing_currencies[$code][$pricegroup_hash['additional_value']]['auto_value'], ' (Auto)')}
      {/if}
      <tr class="{$bg_value}">
      <td>{$code|wash(xhtml)}</td>
      <td width="1%" style="text-align: right;">{$currency_list[$code].symbol|wash(xhtml)}</td>
      <td>{$first_value|wash(xhtml)}</td>
      <td width="1%" style="text-align: right;">{$currency_list[$code].symbol|wash(xhtml)}</td>
      <td>{$add_value|wash(xhtml)}</td>
      <td>{$currency_object.rate_value}</td>
      </tr>
      {/let}
      {/foreach}
      </table>
      </div>

      <div class="block">
      <label>{"Additional country shipping (first / additional):"|i18n('countryshipping/design/standard/content/datatype/view/ezshipping')}</label>
      <table class="list" border="0">
      <tr>
      <th>{"Country"|i18n('countryshipping/design/standard/content/datatype/view/ezshipping')}</th>
      {foreach $currency_list as $code => $currency_object}
      <th>{"Values (%code)"|i18n('countryshipping/design/standard/content/datatype/view/ezshipping', '', hash('%code', $code|wash(xhtml)))}</th>
      {/foreach}
      </tr>
      <tr class="bglight">
      <td>{"Other countries"|i18n('countryshipping/design/standard/content/datatype/view/ezshipping')}</td>
      {foreach $currency_list as $code => $currency_object}
      {let $first_value=$additional_country_default[$code][$pricegroup_hash['first_value']]['value']
           $add_value=$additional_country_default[$code][$pricegroup_hash['additional_value']]['value']}

      {if is_set($additional_country_default[$code][$pricegroup_hash['first_value']]['auto_value'])}
         {set first_value=$additional_country_default[$code][$pricegroup_hash['first_value']]['auto_value']}
      {/if}
      {if is_set($additional_country_default[$code][$pricegroup_hash['additional_value']]['auto_value'])}
         {set add_value=concat($additional_country_default[$code][$pricegroup_hash['additional_value']]['auto_value'], ' (Auto)'|i18n('countryshipping/design/standard/content/datatype/view/ezshipping'))}
      {/if}
      <td>{$currency_object.symbol|wash(xhtml)}&nbsp;{$first_value|wash(xhtml)} / {$add_value|wash(xhtml)}</td>
      {/let}
      {/foreach}
      </tr>
    {foreach $additional_country_list as $country_name => $additional_country
             sequence array('bgdark', 'bglight') as $bg_value}
      <tr class="{$bg_value}">
      <td>{$country_name|wash(xhtml)}</td>
      {foreach $currency_list as $code => $currency_object}
      {let $preferred=$code|eq($preferred_currency_code)
           $first_value=$additional_country[$code][$pricegroup_hash['first_value']]['value']
           $add_value=$additional_country[$code][$pricegroup_hash['additional_value']]['value']}

      {if is_set($additional_country[$code][$pricegroup_hash['first_value']]['auto_value'])}
         {set first_value=$additional_country[$code][$pricegroup_hash['first_value']]['auto_value']}
      {/if}
      {if is_set($additional_country[$code][$pricegroup_hash['additional_value']]['auto_value'])}
         {set add_value=concat($additional_country[$code][$pricegroup_hash['additional_value']]['auto_value'], ' (Auto)'|i18n('countryshipping/design/standard/content/datatype/view/ezshipping'))}
      {/if}
      <td>{$currency_object.symbol|wash(xhtml)}&nbsp;{$first_value|wash(xhtml)} / {$add_value|wash(xhtml)}</td>
      {/let}
      {/foreach}
      </tr>
    {/foreach}
      </table>
     </div>
{/let}