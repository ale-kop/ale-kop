<div class="space-y-2 px-4 lg:px-0">
    @forelse($posts as $post)
        <a class="bg-zinc-200 dark:bg-slate-700 border border-transparent hover:border-gray-300 bg-opacity-50 hover:bg-opacity-75 rounded-md hover:shadow active:shadow-sm flex transition-all duration-200 ease-in-out justify-start items-center"
           href="{{route($route ?? 'posts.show', ['courseSlug' => $post->course->slug ?? null, 'post' => $post->slug])}}" title="Ler {{$post->name}}">
            <div class="p-3">
                <div class="w-16 h-16 rounded-full"
                     style="background: url('{{($post->getMedia('post-image')->first()) ? $post->getMedia('post-image')->first()->getUrl('thumb') : asset('/img/white-desk-woman-macbook.jpeg')}}') center no-repeat; background-size: cover;"></div>
            </div>
            <div class="flex flex-col space-y-2 p-4">
                <h1 class="font-metropolis font-medium text-base md:text-xl text-zinc-700 dark:text-zinc-200 inline-flex break-all">
                    {{$post->name}}
                    @auth @isset($post->extra['module']) <span class="text-red-700 text-base">&nbsp;{{$post->extra['module']}} ({{$post->extra['module_order']}})</span> @endisset @endauth
                </h1>
                <p class="text-sm font-light pr-4 flex items-center gap-2">{{$post->meta['description'] ?? ''}}
                    @php
                        $readable = $post->course_id !== null;
                        $isRead = $readable ? ($post->is_read ?? $post->isReadBy(auth()->user())) : false;
                    @endphp
                    <span class="inline-flex items-center text-[11px] font-medium px-2 py-0.5 rounded {{ $isRead ? 'bg-emerald-100 text-emerald-700' : ($readable ? 'bg-gray-100 text-gray-600' : 'bg-gray-50 text-gray-400') }}">
                        {{ $isRead ? 'Lido' : ($readable ? 'Não lido' : 'Avulso') }}
                    </span>
                </p>
            </div>
        </a>
        @auth
            <a href="{{route('posts.edit', $post->id)}}" class="text-red-400 font-medium">Edit</a>
        @endauth
    @empty
        <p class="p-4 text-center">Nenhum post encontrado</p>
    @endforelse
</div>
