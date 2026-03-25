<x-layout title="Painel Admin">
    <x-container>
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">Painel Administrativo</h1>
            <p class="text-gray-500 dark:text-gray-400 mt-1">Gerencie o conteúdo do site</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

            <a href="{{ route('admin.posts') }}"
               class="group flex flex-col gap-3 p-6 rounded-xl border border-gray-200 dark:border-gray-700
                      bg-white dark:bg-gray-800 hover:border-brand hover:shadow-md transition-all">
                <div class="w-10 h-10 rounded-lg bg-brand/10 flex items-center justify-center">
                    <x-heroicon-o-document-text class="w-5 h-5 text-brand"/>
                </div>
                <div>
                    <div class="font-semibold text-gray-900 dark:text-gray-100 group-hover:text-brand transition-colors">Posts</div>
                    <div class="text-sm text-gray-500 mt-0.5">Listar, criar e editar artigos</div>
                </div>
                <div class="mt-auto flex items-center gap-1.5 text-xs font-semibold text-brand opacity-0 group-hover:opacity-100 transition-opacity">
                    Gerenciar <x-heroicon-o-arrow-right class="w-3.5 h-3.5"/>
                </div>
            </a>

            <a href="{{ route('admin.courses') }}"
               class="group flex flex-col gap-3 p-6 rounded-xl border border-gray-200 dark:border-gray-700
                      bg-white dark:bg-gray-800 hover:border-brand hover:shadow-md transition-all">
                <div class="w-10 h-10 rounded-lg bg-accent/10 flex items-center justify-center">
                    <x-heroicon-o-academic-cap class="w-5 h-5 text-accent"/>
                </div>
                <div>
                    <div class="font-semibold text-gray-900 dark:text-gray-100 group-hover:text-brand transition-colors">Cursos</div>
                    <div class="text-sm text-gray-500 mt-0.5">Listar, criar e editar cursos</div>
                </div>
                <div class="mt-auto flex items-center gap-1.5 text-xs font-semibold text-brand opacity-0 group-hover:opacity-100 transition-opacity">
                    Gerenciar <x-heroicon-o-arrow-right class="w-3.5 h-3.5"/>
                </div>
            </a>

            <a href="{{ route('admin.sections') }}"
               class="group flex flex-col gap-3 p-6 rounded-xl border border-gray-200 dark:border-gray-700
                      bg-white dark:bg-gray-800 hover:border-brand hover:shadow-md transition-all">
                <div class="w-10 h-10 rounded-lg bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center">
                    <x-heroicon-o-list-bullet class="w-5 h-5 text-purple-600 dark:text-purple-400"/>
                </div>
                <div>
                    <div class="font-semibold text-gray-900 dark:text-gray-100 group-hover:text-brand transition-colors">Seções</div>
                    <div class="text-sm text-gray-500 mt-0.5">Módulos e capítulos dos cursos</div>
                </div>
                <div class="mt-auto flex items-center gap-1.5 text-xs font-semibold text-brand opacity-0 group-hover:opacity-100 transition-opacity">
                    Gerenciar <x-heroicon-o-arrow-right class="w-3.5 h-3.5"/>
                </div>
            </a>

            <a href="{{ route('admin.tags') }}"
               class="group flex flex-col gap-3 p-6 rounded-xl border border-gray-200 dark:border-gray-700
                      bg-white dark:bg-gray-800 hover:border-brand hover:shadow-md transition-all">
                <div class="w-10 h-10 rounded-lg bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center">
                    <x-heroicon-o-tag class="w-5 h-5 text-emerald-600 dark:text-emerald-400"/>
                </div>
                <div>
                    <div class="font-semibold text-gray-900 dark:text-gray-100 group-hover:text-brand transition-colors">Tags</div>
                    <div class="text-sm text-gray-500 mt-0.5">Categorias e assuntos</div>
                </div>
                <div class="mt-auto flex items-center gap-1.5 text-xs font-semibold text-brand opacity-0 group-hover:opacity-100 transition-opacity">
                    Gerenciar <x-heroicon-o-arrow-right class="w-3.5 h-3.5"/>
                </div>
            </a>

        </div>

        <div class="mt-8 flex gap-3">
            <a href="{{ route('posts.create') }}"
               class="px-4 py-2 rounded-lg bg-brand text-white text-sm font-semibold hover:bg-brand/90 transition-colors">
                + Novo Post
            </a>
            <a href="{{ route('courses.create') }}"
               class="px-4 py-2 rounded-lg border border-gray-200 dark:border-gray-700 text-sm font-semibold
                      hover:border-brand hover:text-brand transition-colors dark:text-gray-300">
                + Novo Curso
            </a>
        </div>

    </x-container>
</x-layout>
