<HTML>
   <HEAD>
      <TITLE>title</TITLE>
          <style>  
            input.private {literal}{ margin-left:20px }{/literal}
            div {literal}{ width: 800px;}{/literal}
          </style>  
   </HEAD>
<form  method="post">
    <div style="margin-left:208px;margin-top:10px"> 
        {html_radios name='private' class='private' options=$radios selected=$showform_params.return_private}
    </div> 
    <div style="margin-left:60px;margin-top:10px"> 
        <label>
            Ваше имя
        </label>
        <input style="margin-left:90px; width:230px" type="text" maxlength="20" value="{$showform_params.namereturn|strip}" name="seller_name">
    </div>
    <div style="margin-left:60px;  margin-top:10px"> 
        <label>Электронная почта</label>
        <input style="margin-left:27px; width:230px;" type="text"  value="{$showform_params.email_return|strip}" name="email">
    </div>
    <div style="margin-left:217px;  margin-top:10px">
        <label> 
            <input type="checkbox" {$showform_params.return_send_email} value="1" name="allow_mails">
            Я не хочу получать вопросы по объявлению по e-mail
        </label>
    </div>
    <div style="margin-left:60px;  margin-top:10px"> 
        <label>Номер телефона</label>
        <input style="margin-left:46px; width:230px" type="text"  value="{$showform_params.phonereturn|strip}" name="phone">
    </div>
    <div style="margin-left:60px;  margin-top:10px"> 
       <label >Город</label> 
       <select style="margin-left:118px; width:230px;height:22px" title="Выберите Ваш город" name="location_id"> 
            <option value="">-- Выберите город --</option>
            <option disabled="disabled">-- Города --</option>
        {html_options options=$cities selected=$showform_params.city}
         </select>
    </div>
    <div style="margin-left:60px;  margin-top:10px"> 
        <label for="fld_category_id" class="form-label">Категория</label> 
            <select style="margin-left:89px; width:230px;height:22px" name="category_id">
                <option value="">-- Выберите категорию --</option>
        {html_options options=$categories selected=$showform_params.returncategory}
            </select> 
    </div>
    <div style="margin-left:60px;  margin-top:10px">
        <label>Название объявления</label> 
        <input style="margin-left:12px; width:230px;" type="text" maxlength="30" placeholder="{$showform_params.notice_field_is_empty}" value="{$showform_params.returntitle|strip}" name="title">
    </div>
    <div style="margin-left:60px;  margin-top:10px"> 
        <label style="position:absolute">Описание объявления</label>
        <textarea style="margin-left:162px; width:230px;height:70px;" maxlength="500" name="description" >{$showform_params.returndescription|strip}</textarea>
    </div>
    <div style="margin-left:60px;  margin-top:10px"> 
        <label >Цена</label>
        <input style="margin-left:124px; width:230px" type="text" maxlength="9"  value="{$showform_params.returnprice|strip}" name="price" >                                                         
    </div>
    <div style="margin-left:221px;  margin-top:10px"> 
        <input style="height:30px;font-weight: 700;color:white;border-radius: 3px;background: rgb(64,199,129);box-shadow: 0 -3px rgb(53,167,110) inset;transition: 0.2s;" type="submit" value="Отправить" name="main_form_submit"  > </div>
    </div>
</form>
    
  <table method="post" style="border: 1px solid black; margin-top:30px;margin-left: 80px">
        <div >
           <tr>
               
                <td><a href="?sortby=title"> |  Название объявления </a></td>
                <td><a href="?sortby=price">  |  Цена </td>
                <td><a href="?sortby=seller_name">  |  Имя </td>
                <td>  |  Удалить | </td>
           </tr>
         </div> 
         <div style="margin-left:111px;  margin-top:10px"> 
            {$showform_params.notice_edit_success}
         </div>
{if $ads_container}
    {foreach from=$ads_container key=key item=arr}
        <tr>
            <td> |  <a href="?formreturn={$key}"> {$arr.title}</a></td>
            <td>  |  {$arr.price}</td>
            <td>  |  {$arr.seller_name}</td>
            <td>  |  <a href="?delentry={$key}">Удалить</a> |</td>
            </tr>  
    {/foreach}
{/if}
