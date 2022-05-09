<?php


class FormShortcode{

    public function render($value){
        if($value == "vip-fakaheda"){
            if(!User::isLoggedIn()) return "<span class=\"red bold\">Pro zobrazení platebního formuláře se musíš přihlásit</span>";
            
            $return = "";
            $return .= '<form action="https://fakaheda.eu/sk/prezentace-serveru/315512-czsk-lextencz-jailbreak-free-vip-stickers/vip_order" class="vip_form active" id="vip_form_220" method="post"><input name="authenticity_token" type="hidden" value="4ae6BX5d+ynUPeMsMNnK1YhN29IGFA4kLXtl/yQsbzQ=">';
            
            $return .= '<div class="row d-flex flex-wrap align-items-center"><div class="col-sm-4">
            <h4 class="mb-3">Paysafecard</h4>
            
            <div class="mt-3">
                <input id="payment_method_457" name="payment_method" type="radio" value="457" >
                <label for="payment_method_457">80 Kč, VIP na 30 dní</label>
            </div>

            <div>
                <input id="payment_method_603" name="payment_method" type="radio" value="603" >
                <label for="payment_method_603" >150 Kč, VIP na 3 měsíce</label>
            </div>

            <div>
                <input id="payment_method_605" name="payment_method" type="radio" value="605" >
                <label for="payment_method_605" >250 Kč, VIP na 6 měsíců</label>
            </div>

            <div>
                <input id="payment_method_607" name="payment_method" type="radio" value="607" >
                <label for="payment_method_607" >400 Kč, VIP na 1 rok</label>
            </div>
                        
  </div>';

      $return.='<div class="col-sm-4">
      <h4 class="mb-0">Platba kartou</h4> <p class="mt-0">VISA/MASTERCARD/MAESTRO</p>
    <div>
        <input id="payment_method_458" name="payment_method" type="radio" value="458" >
        <label for="payment_method_458">80 Kč, VIP na 30 dní</label>
    </div>

    <div>
        <input id="payment_method_604" name="payment_method" type="radio" value="604" >
        <label for="payment_method_604">150 Kč, VIP na 3 měsíce</label>
    </div>

    <div>
        <input id="payment_method_606" name="payment_method" type="radio" value="606" >
        <label for="payment_method_606">250 Kč, VIP na 6 měsíců</label>
    </div>
                     
    <div>
        <input id="payment_method_608" name="payment_method" type="radio" value="608" >
        <label for="payment_method_608">400 Kč, VIP na 1 rok</label>
    </div>
    </div>
';
            $return .= '<div class="col-sm-4"><h4 class="mb-3">Doplnující informace</h4><input id="variables_307" name="variables[307]" placeholder="Accountid" required="required" type="text" value="'.round(User::steamid64_to_accountid(User::getUser()["steamid"]),0).'"><input id="email" name="email" placeholder="E-mail" required="required" type="email">';
            $return .= '<div class="mt-2">           
            <input id="conditions_confirmed_294" name="conditions_confirmed" required="required" type="checkbox" value="1">
            <label for="conditions_confirmed_294">Souhlasím s</label> <a href="https://fakaheda.eu/reklamace-vip-platby" target="_blank">obchodními podmínkami</a></div>';
            $return .= "<input id=\"submit-220\" type=\"submit\" value=\"Zakoupit\" class=\"btn btn-success\"></div></div></form>";
            return $return;
        }
    }

}