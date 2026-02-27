<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|regex:/^[\pL\s\-]+$/u',
            'lastname' => 'required|string|max:100|regex:/^[\pL\s\-]+$/u',
            'email' => 'required|email',
            'phone' => 'required|string|max:20|regex:/^\d+$/',
            'comments' => 'required|string|min:20',
        ], [
            'name.required' => 'El nombre es obligatorio.',
            'name.regex' => 'El nombre no debe contener números.',
            'lastname.required' => 'El apellido es obligatorio.',
            'lastname.regex' => 'El apellido no debe contener números.',
            'email.required' => 'El email es obligatorio.',
            'email.email' => 'Ingresá un email válido.',
            'phone.required' => 'El teléfono es obligatorio.',
            'phone.regex' => 'Ingresá un número de teléfono válido (solo números).',
            'comments.required' => 'El mensaje es obligatorio.',
            'comments.min' => 'El mensaje debe tener al menos 20 caracteres.',
        ]);

        $to = 'carpirok@gmail.com';
        $subject = 'Nuevo mensaje de contacto';
        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8\r\n";

        $name = htmlspecialchars($validated['name']);
        $lastName = htmlspecialchars($validated['lastname']);
        $email = htmlspecialchars($validated['email']);
        $phone = htmlspecialchars($validated['phone']);
        $message = nl2br(htmlspecialchars($validated['comments']));

        $body = <<<HTML
<html lang="es">
<head><meta charset="UTF-8"></head>
<body>
<div style="width:600px;border:1px solid #4e4e4e;margin:2rem auto;font-family:system-ui,sans-serif">
<table style="width:600px">
<thead>
<tr><th colspan="2" style="font-weight:700;font-size:1.2rem;border-bottom:1px solid #4e4e4e;background:#2c2c2c;color:#f7f7f7;padding:0.5rem">Nuevo correo de contacto</th></tr>
</thead>
<tbody>
<tr>
<td style="padding:1rem;border-bottom:1px solid #4e4e4e"><strong>Nombre:</strong> {$name}</td>
<td style="padding:1rem;border-bottom:1px solid #4e4e4e"><strong>Apellido:</strong> {$lastName}</td>
</tr>
<tr>
<td style="padding:1rem;border-bottom:1px solid #4e4e4e"><strong>Mail:</strong> {$email}</td>
<td style="padding:1rem;border-bottom:1px solid #4e4e4e"><strong>Teléfono:</strong> {$phone}</td>
</tr>
<tr>
<td colspan="2" style="padding:1rem"><strong>Mensaje:</strong><br>{$message}</td>
</tr>
</tbody>
</table>
</div>
</body>
</html>
HTML;

        if (@mail($to, $subject, $body, $headers)) {
            return redirect()->route('home')->with('contact_success', '¡Mensaje enviado! Te contactaremos pronto.');
        }

        return back()->withInput()->with('contact_error', 'Hubo un error al enviar el mensaje. Por favor, intentá nuevamente.');
    }
}
