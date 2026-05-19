<h2>Password Reset</h2>
<p>Dear [Applicant Name],</p>
<p>Thank you for applying for the [Scholarship Name]. We have reviewed your application and would like to inform you that:</p>

 <a href="{{ url(route('password.reset', ['token' => $token, 'email' => $user->email], false)) }}"
           style="display: inline-block; padding: 10px 20px; color: #ffffff; background-color: #3490dc; text-decoration: none; border-radius: 5px;">
            Reset Password
        </a>
<p>If approved, further instructions and documentation will be sent shortly.
</p>

<p>For questions, please contact [Scholarship Office Email].</p>
<p> Best regards, </p>
<p>[Institution/Company Name] Scholarship Committee</p>
