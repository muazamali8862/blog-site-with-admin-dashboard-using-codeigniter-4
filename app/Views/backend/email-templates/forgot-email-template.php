<p>Dear <?= $mail_data['user']->name ?></p>
<p>
    We are recieved a request  to reset your password for blog account associated with <i><?= $mail_data['user']->email ?></i>.
    You can reset your password by clicking the button below:
    <br><br>
    <a href="<?= $mail_data['actionLink'] ?>" target="_blank" style="color:#fff; border-color: #22bc66;border-style: solid; border-width: 5px 10px; background-color: #22bc66; display:inline-block; text-decoration:none; border-radius: 3px; box-shadow: 0 2px 3px rgba(0,0,0,0.16);-webkit-text-size-adjust:none; box-sizing:border-box;">Reset password</a> 
    <br><br>
    <b>NB:</b> This link will be valid whithin 15 minutes.
    <br><br>
    if you  did not request a password reset, please ignore this email.

</p>