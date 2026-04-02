<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        $request->merge([
            'name' => trim((string) $request->input('name')),
            'lastname' => trim((string) $request->input('lastname')),
            'email' => trim((string) $request->input('email')),
            'phone' => trim((string) $request->input('phone')),
            'comments' => trim((string) $request->input('comments')),
            'website' => trim((string) $request->input('website')),
        ]);

        $validated = $request->validate([
            'name' => 'required|string|max:100|regex:/^[\pL\s\-]+$/u',
            'lastname' => 'required|string|max:100|regex:/^[\pL\s\-]+$/u',
            'email' => 'required|email',
            'phone' => 'required|string|max:30|regex:/^[0-9\s()+-]+$/',
            'comments' => 'required|string',
            'website' => 'nullable|max:0',
        ], [
            'name.required' => 'El nombre es obligatorio.',
            'name.regex' => 'El nombre no debe contener números.',
            'lastname.required' => 'El apellido es obligatorio.',
            'lastname.regex' => 'El apellido no debe contener números.',
            'email.required' => 'El email es obligatorio.',
            'email.email' => 'Ingresá un email válido.',
            'phone.required' => 'El teléfono es obligatorio.',
            'phone.regex' => 'Ingresá un número de teléfono válido.',
            'comments.required' => 'El mensaje es obligatorio.',
            'website.max' => 'Solicitud inválida.',
        ]);

        if (!empty($validated['website'])) {
            return redirect()->to(route('home') . '#contacto')->with('contact_success', '¡Mensaje enviado! Te contactaremos pronto.');
        }

        $to = 'carpirok@gmail.com';
        $subject = 'Nuevo mensaje de contacto - ' . $validated['name'] . ' ' . $validated['lastname'];

        $name = e($validated['name']);
        $lastName = e($validated['lastname']);
        $email = e($validated['email']);
        $phone = e($validated['phone']);
        $message = nl2br(e($validated['comments']));

        $body = <<<HTML
<html lang="es">
<head><meta charset="UTF-8"></head>
<body>
<div style="max-width:640px;border:1px solid #d9dce3;border-radius:16px;overflow:hidden;margin:2rem auto;font-family:Arial,sans-serif;background:#ffffff">
<table style="width:100%;border-collapse:collapse">
<thead>
<tr><th colspan="2" style="font-weight:700;font-size:1.2rem;background:#152238;color:#f7f7f7;padding:1rem 1.25rem;text-align:left">Nuevo mensaje desde carpir.com.ar</th></tr>
</thead>
<tbody>
<tr>
<td style="padding:1rem 1.25rem;border-bottom:1px solid #e8ebf1"><strong>Nombre:</strong> {$name}</td>
<td style="padding:1rem 1.25rem;border-bottom:1px solid #e8ebf1"><strong>Apellido:</strong> {$lastName}</td>
</tr>
<tr>
<td style="padding:1rem 1.25rem;border-bottom:1px solid #e8ebf1"><strong>Email:</strong> {$email}</td>
<td style="padding:1rem 1.25rem;border-bottom:1px solid #e8ebf1"><strong>Teléfono:</strong> {$phone}</td>
</tr>
<tr>
<td colspan="2" style="padding:1rem 1.25rem"><strong>Mensaje:</strong><br><br>{$message}</td>
</tr>
</tbody>
</table>
</div>
</body>
</html>
HTML;

        try {
            $mailer = config('mail.default');

            if (in_array($mailer, ['log', 'array'], true)) {
                $fromAddress = (string) config('mail.from.address', 'no-reply@carpir.com.ar');
                $fromName = (string) config('mail.from.name', 'Carpir');
                $headers = [
                    'MIME-Version: 1.0',
                    'Content-type:text/html;charset=UTF-8',
                    'From: ' . $fromName . ' <' . $fromAddress . '>',
                    'Reply-To: ' . $validated['email'],
                ];

                if (!mail($to, $subject, $body, implode("\r\n", $headers))) {
                    throw new \RuntimeException('No se pudo enviar el correo con la función mail().');
                }
            } else {
                Mail::html($body, function ($mailMessage) use ($to, $subject, $validated) {
                    $mailMessage->to($to)
                        ->subject($subject)
                        ->replyTo($validated['email'], $validated['name'] . ' ' . $validated['lastname']);
                });
            }

            return redirect()->to(route('home') . '#contacto')->with('contact_success', '¡Mensaje enviado! Te contactaremos pronto.');
        } catch (\Throwable $e) {
            Log::error('Contact form mail failed', [
                'message' => $e->getMessage(),
                'exception' => get_class($e),
                'trace' => $e->getTraceAsString(),
            ]);
            report($e);
        }

        return back()->withInput()->with('contact_error', 'No pudimos enviar tu mensaje. Revisá storage/logs/laravel.log en el servidor para más detalles.');
    }
}
