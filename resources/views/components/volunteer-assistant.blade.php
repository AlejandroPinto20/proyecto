<div id="volunteer-assistant" class="fixed bottom-5 right-5 z-50 w-80 max-w-[calc(100vw-2.5rem)]">
    <button id="assistant-toggle" type="button" class="ml-auto flex items-center gap-2 rounded-full bg-blue-700 px-5 py-3 font-semibold text-white shadow-lg hover:bg-blue-800">
        <span aria-hidden="true">AI</span>
        Ayuda del sistema
    </button>

    <section id="assistant-panel" class="mt-3 hidden overflow-hidden rounded-xl bg-white shadow-2xl ring-1 ring-black/10">
        <header class="bg-blue-700 px-4 py-3 text-white">
            <h2 class="font-semibold">Asistente de voluntariado</h2>
            <p class="text-xs text-blue-100">Responde sobre las funciones de esta aplicación.</p>
        </header>

        <div id="assistant-messages" class="h-72 space-y-3 overflow-y-auto bg-slate-50 p-4 text-sm" aria-live="polite">
            <div class="rounded-lg bg-white p-3 text-slate-700 shadow-sm">Hola. Puedo orientarte sobre actividades, inscripciones, certificados, rankings, donaciones y tu perfil.</div>
        </div>

        <form id="assistant-form" class="border-t border-slate-200 p-3">
            <label class="sr-only" for="assistant-input">Pregunta al asistente</label>
            <div class="flex gap-2">
                <input id="assistant-input" maxlength="500" required class="min-w-0 flex-1 rounded-lg border-slate-300 text-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Escribe tu pregunta" />
                <button type="submit" class="rounded-lg bg-blue-700 px-3 py-2 text-sm font-semibold text-white hover:bg-blue-800">Enviar</button>
            </div>
        </form>
    </section>
</div>
