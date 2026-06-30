<x-layouts.app title="Política de tratamiento de datos">
    <div class="flex flex-1 flex-col px-6 pt-8 pb-12">
        <a href="{{ url()->previous() }}" class="inline-flex items-center gap-1 text-sm font-medium text-navy-400 hover:text-navy-600">
            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
            Volver
        </a>

        <h1 class="mt-5 text-2xl font-semibold text-navy-700">Política de tratamiento de datos</h1>
        <p class="mt-1 text-sm text-navy-400">Conforme a la Ley 1581 de 2012 (Colombia) · Última actualización: {{ now()->isoFormat('D [de] MMMM [de] YYYY') }}</p>

        <div class="mt-6 space-y-5 text-sm leading-relaxed text-navy-600">
            <section>
                <h2 class="font-semibold text-navy-700">1. Responsable</h2>
                <p>Mind &amp; Health es responsable del tratamiento de los datos personales que nos suministras.
                Contacto: {{ config('mail.from.address', 'datos@mindhealth.co') }}.</p>
            </section>
            <section>
                <h2 class="font-semibold text-navy-700">2. Datos que recolectamos</h2>
                <p>Nombre, correo electrónico, teléfono y la información necesaria para agendar y pagar tu cita.
                Los datos relacionados con tu salud son sensibles y reciben protección reforzada.</p>
            </section>
            <section>
                <h2 class="font-semibold text-navy-700">3. Finalidad</h2>
                <p>Usamos tus datos para crear tu cuenta, gestionar tus citas, procesar pagos, enviarte
                confirmaciones y recordatorios, y mejorar el servicio. No vendemos tus datos a terceros.</p>
            </section>
            <section>
                <h2 class="font-semibold text-navy-700">4. Autorización</h2>
                <p>Al registrarte y aceptar esta política, autorizas el tratamiento de tus datos para las
                finalidades descritas. El suministro de datos sensibles es facultativo.</p>
            </section>
            <section>
                <h2 class="font-semibold text-navy-700">5. Tus derechos</h2>
                <p>Puedes conocer, actualizar, rectificar y suprimir tus datos, y revocar la autorización,
                escribiéndonos a {{ config('mail.from.address', 'datos@mindhealth.co') }}.</p>
            </section>
            <section>
                <h2 class="font-semibold text-navy-700">6. Seguridad</h2>
                <p>Aplicamos medidas técnicas y administrativas (cifrado en tránsito, control de acceso por roles,
                contraseñas protegidas) para resguardar tu información.</p>
            </section>
        </div>

        <p class="mt-8 text-center text-xs text-navy-300">
            <a href="{{ route('legal.terminos') }}" class="font-medium text-navy-500 hover:underline">Ver Términos y condiciones</a>
        </p>
    </div>
</x-layouts.app>
