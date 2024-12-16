@component('mail::message')
# Reclamación Recibida

**Nombre:** {{ $data['names'] }}
**Apellidos:** {{ $data['lastnames'] }}

**Correo electrónico:** {{ $data['email'] }}

**Celular:** {{ $data['phone'] }}


**Descripción del reclamo:**
{{ $data['message'] }}


Gracias,<br>
{{ config('app.name') }}
@endcomponent