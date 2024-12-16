@component('mail::message')
# SOLICITUD DE CONTACTO
<br>
<span>Nombres:</span>
<p>{{ $names }}</p>
<span>Correo:</span>
<p>{{ $email }}</p>
<span>Mensaje:</span>
<p>{{ $message }}</p>
@endcomponent
