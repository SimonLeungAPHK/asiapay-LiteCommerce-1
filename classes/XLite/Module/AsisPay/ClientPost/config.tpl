{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * AsiaPay ClientPost configuration page
 *
 * @author    AsiaPay Limited
 * @copyright Copyright (c) 2012 AsiaPay Limited. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.11
 *}

<table cellspacing="1" cellpadding="5" class="settings-table">

  <tr>
    <td class="setting-name">
    <label>Payment URL:</label>
    </td>
    <td>
    <input type="text" id="settings_payment_url" name="settings[payment_url]" value="{paymentMethod.getSetting(#payment_url#)}" class="field-required" />
    </td>
  </tr>

  <tr>
    <td class="setting-name">
    <label>Merchant Id (merchantId):</label>
    </td>
    <td>
    <input type="text" id="settings_merchant_id" name="settings[merchant_id]" value="{paymentMethod.getSetting(#merchant_id#)}" class="field-required" />
    </td>
  </tr>
  
  <tr>
    <td class="setting-name">
    <label>Currency Code (currCode): </label>
    </td>
    <td>
      <select id="settings_curr_code" name='settings[curr_code]'>
        <option value="344">HKD</option>
        <option value="840">USD</option>
        <option value="702">SGD</option>
        <option value="156">CNY (RMB)</option>
        <option value="392">JPY</option>
        <option value="901">TWD</option>
        <option value="036">AUD</option>
        <option value="978">EUR</option>
        <option value="826">GBP</option>
        <option value="124">CAD</option>
        <option value="446">MOP</option>
        <option value="608">PHP</option>
        <option value="764">THB</option>
        <option value="458">MYR</option>
        <option value="360">IDR</option>
        <option value="410">KRW</option>
        <option value="682">SAR</option>
        <option value="554">NZD</option>
        <option value="784">AED</option>
        <option value="096">BND</option>
      </select>
    </td>
  </tr>
  
  <tr>
    <td class="setting-name">
      <label>Payment Type (payType): </label>
    </td>
    <td>
      <select id="settings_pay_type" name='settings[pay_type]'>
        <option value="N">Sale (N=Normal)</option>
        <option value="H">Authorize (H=Hold)</option>        
      </select>
    </td>
  </tr>
  
  <tr>
    <td class="setting-name">
    <label>Payment Method (payMethod): </label>
    </td>
    <td>
      <select id="settings_pay_method" name='settings[pay_method]'>
        <option value="ALL">ALL</option>
        <option value="CC">CC</option>
        <option value="VISA">VISA</option>
        <option value="Master">Master</option>
        <option value="JCB">JCB</option>
        <option value="AMEX">AMEX</option>
        <option value="Diners">Diners</option>
        <option value="PPS">PPS</option>
        <option value="PAYPAL">PAYPAL</option>
        <option value="CHINAPAY">CHINAPAY</option>
        <option value="ALIPAY">ALIPAY</option>
        <option value="TENPAY">TENPAY</option>
        <option value="99BILL">99BILL</option>
        <option value="MEPS">MEPS</option>
        <option value="BancNet">BancNet</option>
        <option value="GCash">GCash</option>
        <option value="SMARTMONEY">SMARTMONEY</option>
      </select>
    </td>
  </tr>
  
  <tr>
    <td class="setting-name">
    <label>Language (lang): </label>
    </td>
    <td>
      <select id="settings_language" name='settings[language]'>
        <option value="C">Traditional Chinese</option>
        <option value="E">English</option>
        <option value="X">Simplified Chinese</option>
        <option value="K">Korean</option>
        <option value="J">Japanese</option>
        <option value="T">Thai</option>
        <option value="F">French</option>
        <option value="G">German</option>
        <option value="R">Russian</option>
        <option value="S">Spanish</option>
      </select>
    </td>
  </tr>
  
  <tr>
    <td class="setting-name">
    <label>Prefix on Order Reference Number: </label>
    </td>
    <td>
    <input type="text" id="settings_prefix" name="settings[prefix]" value="{paymentMethod.getSetting(#prefix#)}" class="field-required" />
    </td>
  </tr>

  <tr>
    <td class="setting-name">
    <label>Secure Hash Secret: </label>
    </td>
    <td>
    <input type="text" id="settings_secure_hash_secret" name="settings[secure_hash_secret]" value="{paymentMethod.getSetting(#secure_hash_secret#)}"  />
    </td>
  </tr>

  <tr>
    <td class="setting-name">
    <label>Transaction Type: </label>
    </td>
    <td>
      <select id="settings_transaction_type" name='settings[transaction_type]'>
        <option value="01">Goods/ Service Purchase</option>
        <option value="03">Check Acceptance</option>
        <option value="10">Account Funding</option>
        <option value="11">Quasi-Cash Transaction</option>
        <option value="28">Quasi-Cash Transaction</option>
      </select>
    </td>
  </tr>

  <tr>
    <td class="setting-name">
    <label>Challenge Preference: </label>
    </td>
    <td>
      <select id="settings_challenge_preference" name='settings[challenge_preference]'>
        <option value="01">No preference</option>
        <option value="02">No challenge requested *</option>
        <option value="03">Challenge requested (Merchant preference)</option>
        <option value="04">Challenge requested (Mandate)</option>
        <option value="05">No challenge requested (transactional risk analysis is already performed) *</option>
        <option value="06">No challenge requested (Data share only)*</option>
        <option value="07">No challenge requested (strong consumer authentication is already performed) *</option>
        <option value="08">No challenge requested (utilise whitelist exemption if no challenge required) *</option>
        <option value="09">Challenge requested (whitelist prompt requested if challenge required)</option>
      </select>
    </td>
  </tr>

 
</table>
<br/>
<table border="0">
 <tr>
    <td colspan="2">
    	Note: Set the datafeed URL at your AsiaPay Merchant Admin Panel > Profile > Profile Settings > Payment Options > Return Value Link (Datafeed) <br/>
		E.g. http://www.your_litecommerce_site.com/cart.php?target=callback&txn_id_name=remark
    </td>
  </tr>
</table>

<script type="text/javascript">
  jQuery("#settings_curr_code").val("{paymentMethod.getSetting(#curr_code#)}");
  jQuery("#settings_pay_type").val("{paymentMethod.getSetting(#pay_type#)}");
  jQuery("#settings_pay_method").val("{paymentMethod.getSetting(#pay_method#)}");
  jQuery("#settings_language").val("{paymentMethod.getSetting(#language#)}");
  jQuery("#settings_transaction_type").val("{paymentMethod.getSetting(#transaction_type#)}");
  jQuery("#settings_challenge_preference").val("{paymentMethod.getSetting(#challenge_preference#)}");
</script>

  