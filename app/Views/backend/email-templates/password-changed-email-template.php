<p>Dear <b><?= $mail_data['user']->name ?></b></p>
<br>
<p>
    Your Password on blog system was changed successfully. Here are your new login credentials: 
    <br><br>
    <b>Login ID:</b> <?= $mail_data['user']->username?> or <?= $mail_data['user']->email ?>
    <br>
    <b>Password: </b> <?=  $mail_data['new_password'] ?>
</p>
<br><br>
Please, Keep your credentials confidentials. Your Username and Password are your own credentials and you should never share with anybody else.
<p>
    Blog will not be liable for any misuse of your Username and Password.
</p>
<br>
------------------------------------------------
<p>
    This email was automatically sent by blog system. Do not reply it.
</p>