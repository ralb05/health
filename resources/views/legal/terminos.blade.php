<x-layouts.app title="Términos y condiciones">
    <div class="flex flex-1 flex-col px-6 pt-8 pb-12">
        <a href="{{ url()->previous() }}" class="inline-flex items-center gap-1 text-sm font-medium text-navy-400 hover:text-navy-600">
            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
            Volver
        </a>

        <h1 class="mt-5 text-2xl font-semibold text-navy-700">Términos y condiciones</h1>
        <p class="mt-1 text-sm text-navy-400">Última actualización: {{ now()->isoFormat('D [de] MMMM [de] YYYY') }}</p>

        <div class="mt-6 space-y-5 text-sm leading-relaxed text-navy-600">
            <section>
                <h2 class="font-semibold text-navy-700">1. Objeto</h2>
                <p>Mind &amp; Health es una plataforma que permite agendar y pagar consultas de salud mental con
                profesionales (psiquiatría y psicología). La plataforma facilita el contacto y la gestión de la cita;
                la atención clínica es responsabilidad del profesional tratante.</p>
            </section>
            <section>
                <h2 class="font-semibold text-navy-700">2. Cuenta y uso</h2>
                <p>Para agendar debes registrarte con datos veraces. Eres responsable de la confidencialidad de tu
                contraseña y de la actividad realizada con tu cuenta.</p>
            </section>
            <section>
                <h2 class="font-semibold text-navy-700">3. Pagos</h2>
                <p>El valor de la consulta se muestra antes de pagar y se cobra a través de la pasarela de pagos
                Mercado Pago. La cita se confirma cuando el pago es aprobado.</p>
            </section>
            <section>
                <h2 class="font-semibold text-navy-700">4. Cancelaciones</h2>
                <p>Puedes cancelar una cita confirmada con la anticipación mínima indicada en la plataforma. Las
                políticas de reembolso se gestionan según el caso.</p>
            </section>
            <section>
                <h2 class="font-semibold text-navy-700">5. Emergencias</h2>
                <p>La plataforma no atiende urgencias. Si estás en crisis o riesgo, comunícate con la línea de
                emergencias o la línea de atención en salud mental de tu localidad.</p>
            </section>
            <section>
                <h2 class="font-semibold text-navy-700">6. Contacto</h2>
                <p>Para dudas sobre estos términos, escríbenos a {{ config('mail.from.address', 'soporte@mindhealth.co') }}.</p>
            </section>
        </div>

        <p class="mt-8 text-center text-xs text-navy-300">
            <a href="{{ route('legal.privacidad') }}" class="font-medium text-navy-500 hover:underline">Ver Política de tratamiento de datos</a>
        </p>
    </div>
</x-layouts.app>
