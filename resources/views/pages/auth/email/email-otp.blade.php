<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>BudgetIn Verification</title>
</head>

<body style="margin:0; padding:0; background-color:#f3f4f6; font-family:Arial, sans-serif;">

    <table width="100%" cellpadding="0" cellspacing="0" style="background-color:#f3f4f6; padding:40px 0;">
        <tr>
            <td align="center">

                <!-- Card -->
                <table width="100%" cellpadding="0" cellspacing="0" style="max-width:480px; background:#ffffff; border-radius:12px; overflow:hidden; box-shadow:0 10px 30px rgba(0,0,0,0.08);">

                    <!-- Header -->
                    <tr>
                        <td style="background:linear-gradient(135deg,#E4EBF1,#B5CFED); padding:24px; text-align:center;">
                            <h2 style="margin:0; font-size:20px; color:#111827;">
                                BudgetIn Verification
                            </h2>
                        </td>
                    </tr>

                    <!-- Content -->
                    <tr>
                        <td style="padding:30px; text-align:center;">

                            <p style="font-size:14px; color:#6b7280; margin-bottom:20px;">
                                Use the verification code below to complete your sign in process.
                            </p>

                            <!-- OTP Box -->
                            <div style="display:inline-block; padding:14px 28px; font-size:28px; letter-spacing:6px; font-weight:bold; color:#111827; background:#f9fafb; border:1px dashed #cbd5e1; border-radius:10px;">
                                {{ $otp }}
                            </div>

                            <p style="margin-top:20px; font-size:13px; color:#6b7280;">
                                This code will expire in <strong>5 minutes</strong>.
                            </p>

                            <!-- Button style (optional future use) -->
                            <a href="#"
                               style="font-weight: bold; display:inline-block; margin-top:20px; padding:10px 18px; background:#3b82f6; color:#ffffff; text-decoration:none; border-radius:8px; font-size:13px;">
                                Return to BudgetIn
                            </a>

                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="padding:16px; text-align:center; font-size:11px; color:#9ca3af; background:#f9fafb;">
                            © {{ date('Y') }} BudgetIn. All rights reserved.
                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>

</body>
</html>