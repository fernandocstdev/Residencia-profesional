<x-datatables.styles />

<x-app-layout>
  <x-slot name="header">
    <div class="flex items-center gap-1 pt-1">
      <x-nav-link href="{{ route('acuerdos-cu') }}">
        Acuerdos C.U
      </x-nav-link>
      <x-arrow />
      <x-nav-link href="{{ route('samaras.index') }}">
        Samarás
      </x-nav-link>
      <x-arrow />
      <x-nav-link :active="true">
        Menendez Samara {{ $samara->numero }}
      </x-nav-link>
      <x-arrow />
      <x-nav-link>
        Editar
      </x-nav-link>
    </div>
  </x-slot>

  <section class="flex flex-col mt-10 mb-20 sm:px-20">
    <article class="card-container">
      <div>
        <h3 class="title">Menendez Samara {{ $samara->numero }}</h3>
        <p class="text-secondary">Actualice la informacion del samara.</p>
      </div>

      <form method="post" action="{{ route('samaras.update', ['samara' => $samara->id]) }}" class="mt-3">
        @csrf
        @method('patch')
        <div class="w-1/3 mt-6">
          <x-input-label for="anio" :value="__('Año')" />
          <x-text-input id="anio" name="anio" type="text" class="block mt-1 w-fit" :value="$samara->anio"
            autofocus />
          <x-input-error class="mt-2" :messages="$errors->get('anio')" />
        </div>

        <div class="w-1/3 mt-6">
          <x-input-label for="numero" :value="__('Numero')" />
          <x-text-input id="numero" name="numero" type="text" class="block mt-1 w-fit" :value="$samara->numero" />
          <x-input-error class="mt-2" :messages="$errors->get('numero')" />
        </div>

        <div class="w-1/3 mt-6">
          <x-input-label for="rectorado" :value="__('Rectorado')" />
          <select name="rectorado" id="rectorado" class="mt-2 border-gray-300 rounded-md border-1">
            <option value="">Elige un rectorado</option>
            @foreach ($rectorados as $rectorado)
              <option value="{{ $rectorado->id }}" @if (old('state', $samara->rectorado_id) == $rectorado->id) {{ 'selected' }} @endif>
                {{ $rectorado->ciclo }}</option>
            @endforeach
          </select>
          <x-input-error class="mt-2" :messages="$errors->get('rectorado')" />
        </div>

        <div class="w-1/3 mt-6">
          <x-input-label for="fecha" :value="__('Fecha')" />
          <input type="date" name="fecha" id="fecha" class="mt-2 border-gray-300 rounded-md border-1"
            value="{{ Carbon\Carbon::parse($samara->fecha)->format('Y-m-d') }}">
          <x-input-error class="mt-2" :messages="$errors->get('fecha')" />
        </div>

        <div class="w-2/3 mt-6">
          @if ($samara->url_archivo == null)
            <x-input-label for="url_archivo" :value="__('URL documento PDF')" />
            <x-text-input id="url_archivo" name="url_archivo" type="text" class="block w-full mt-1"
              :value="$samara->url_archivo" autofocus />
            <x-input-error class="mt-2" :messages="$errors->get('url_archivo')" />
          @else
            <div>
              <x-input-label for="url_archivo" :value="__('URL documento PDF')" />
              <x-text-input id="url_archivo" name="url_archivo" type="text" class="block w-full mt-1 text-gray-500"
                :value="$samara->url_archivo" autofocus />
              <x-input-error class="mt-2" :messages="$errors->get('url_archivo')" />
            </div>
          @endif
        </div>

        <div class="flex items-center gap-2 mt-10">
          <x-primary-button class="gap-2" x-data="{ loading: false }" x-on:click="loading = true">
            <span>Acualizar</span>
            <span x-show="loading">
              <x-loaders.spinner />
            </span>
          </x-primary-button>
          @if ($message = Session::get('success'))
            <x-alerts.success :text="$message" />
          @elseif ($message = Session::get('warning'))
            <x-alerts.warning :text="$message" />
          @endif
        </div>

        <div class="mt-1">
          <p class="text-secondary">Actializado ultima vez: {{ $samara->updated_at }} </p>
        </div>
      </form>

    </article>

    <article class="mt-6 card-container">
      <div class="sm:w-1/2 p-3 border-[1px] border-gray-600 rounded">
        <h3 class="title">Sesiones del Samará</h3>
        <p class="mt-3 mb-1 text">Elimina sesiones al samará</p>
        <p class="mb-2 text-secondary">Seleccione las sesiones que desea eliminar</p>
        @foreach ($samara->samarasesion as $item)
          <div class="mt-2 sm:w-1/2">
            <form action="{{ route('samarasesion.delete', ['samara' => $samara->id, 'sesion' => $item->sesion->id]) }}"
              method="POST">
              @method('delete')
              @csrf
              <div class="flex border-gray-300 rounded border-[1px] p-2 justify-between">
                <div class="flex items-center gap-1">
                  <p class="text">Sesión {{ $item->sesion->sesionTipo->tipo }}</p>
                  <p class="text"> : {{ $item->sesion->fecha }}</p>
                </div>
                <button type="button" x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm')">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round"
                      d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                  </svg>
                </button>
                <x-modal name="confirm">
                  <div class="p-5">
                    <h4 class="title">Atencion!</h4>
                    <p class="text">¿Seguro que quiere eliminar esta sesion?</p>
                    <div class="flex justify-end gap-2 mt-4">
                      <x-primary-button x-on:click="$dispatch('close')" type="button">No, cancelar</x-primary-button>
                      <x-danger-button class="gap-2" x-data="{ loading: false }" x-on:click="loading = true">
                        <span>Si, eliminar</span>
                        <span x-show="loading">
                          <x-loaders.spinner />
                        </span>
                      </x-danger-button>
                    </div>
                  </div>
                </x-modal>
              </div>
            </form>
          </div>
        @endforeach
        <div class="my-2">
          @if ($message = Session::get('deleted'))
            <x-alerts.warning :text="$message" />
          @endif
        </div>
      </div>

      <div class="sm:w-1/2 p-3 border-[1px] border-gray-800 rounded mt-3">
        <p class="mb-1 text">Agrega sesiones al samará</p>
        <p class="mb-2 text-secondary">Seleccione las sesiones que desea agregar</p>
        <form action="{{ route('samarasesion.add', ['samara' => $samara->id]) }}" method="post">
          @csrf
          @method('post')
          @foreach ($sesiones as $sesion)
            @if ($sesion->samarasesion == null)
              <div class="w-full flex gap-3 border-[1px] sm:w-fit border-gray-300 rounded p-2 items-center mb-2">
                <input type="checkbox" value="{{ $sesion->id }}" class="ml-3 " name="sesion[]"
                  id="{{ $sesion->id }}">
                <div class="flex items-center gap-1">
                  <p class="text">Sesión {{ $sesion->sesionTipo->tipo }}</p>
                  <p class="text"> : {{ $sesion->fecha }}</p>
                </div>
              </div>
            @endif
          @endforeach
          <x-primary-button class="mt-3" x-data="{ loading: false }" x-on:click="loading = true">
            <span>Agregar</span>
            <span x-show="loading">
              <x-loaders.spinner />
            </span>
          </x-primary-button>
        </form>

      </div>
    </article>

    <article class="mt-6 card-container">
      <section class="flex flex-col sm:w-1/2 border-[1px] rounded border-red-500">

        <div class="flex items-center justify-between py-3 border-b-[1px] border-gray-300  px-3">
          <div class="flex flex-col justify-center w-1/2 sm:w-2/3">
            <h4 class="title">Eliminar Samará</h4>
            <p class="text-secondary">Elimina este samará, no se podra recuperar este registro, por favor,
              este
              seguro.</p>
          </div>
          <x-danger-button x-data=""
            x-on:click.prevent="$dispatch('open-modal', 'confirm-rectorado-delete')" class="">
            Eliminar
          </x-danger-button>

          <x-modal name="confirm-rectorado-delete">
            <div class="p-5">
              <form method="post" action="{{ route('samaras.destroy', ['samara' => $samara->id]) }}">
                @csrf
                @method('delete')
                <h2 class="title">
                  {{ __('¿Esta seguro que quiere eliminar este Rectorado?') }}
                </h2>
                <p class="text-secondary">
                  {{ __('Ingrese su contraseña para confirmar que desea eliminar el samara de forma permanente.') }}
                </p>
                <div class="mt-6">
                  <x-input-label for="password" value="{{ __('Password') }}" class="sr-only" />
                  <x-text-input id="password" name="password" type="password" class="block w-3/4 mt-1"
                    placeholder="{{ __('Password') }}" />
                  <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
                </div>

                <div class="flex justify-end mt-10">
                  <x-primary-button type="button" x-on:click="$dispatch('close')" class="btn-cancel-delete">
                    {{ __('Cancelar') }}
                  </x-primary-button>

                  <x-danger-button class="ml-3">
                    {{ __('Eliminar samará') }}
                  </x-danger-button>
                </div>
              </form>
            </div>
          </x-modal>
        </div>

        @if ($samara->status)
          <div class="flex items-center justify-between px-3 py-3">
            <div class="flex flex-col justify-center w-1/2 sm:w-2/3">
              <h4 class="title">Archivar Samará</h4>
              <p class="text-secondary">Archiva este samará, lo podras recuperar en un futuro si asi lo
                deseas.
              </p>
            </div>
            <x-danger-button x-data=""
              x-on:click.prevent="$dispatch('open-modal', 'confirm-file-rectorado')">
              Archivar
            </x-danger-button>
            <x-modal name="confirm-file-rectorado">
              <div>
                <form method="post" class="p-6" action="{{ route('samaras.file', ['samara' => $samara->id]) }}">
                  @csrf
                  @method('patch')
                  <h2 class="title">
                    {{ __('¿Esta seguro que quiere archivar este Samará?') }}
                  </h2>
                  <p class="text-secondary">
                    {{ __('Ingrese su contraseña para confirmar que desea archivar el samara') }}
                  </p>
                  <div class="mt-6">
                    <x-input-label for="password" value="{{ __('Password') }}" class="sr-only" />
                    <x-text-input id="passwordArchivar" name="password" type="password" class="block w-3/4 mt-1"
                      placeholder="{{ __('Password') }}" />
                    <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
                  </div>
                  <div class="flex justify-end mt-6">
                    <x-primary-button type="button" x-on:click="$dispatch('close')">
                      {{ __('Cancelar') }}
                    </x-primary-button>
                    <x-danger-button class="ml-3">
                      {{ __('Archivar rectorado') }}
                    </x-danger-button>
                  </div>
                </form>
              </div>
            </x-modal>
          </div>
        @elseif (!$samara->status)
          <div class="flex items-center justify-between px-3 py-3 ">
            <div class="flex flex-col justify-center w-1/2 sm:w-2/3">
              <h4 class="font-bold text-md">Recuperar Samará</h4>
              <p class="font-normal text-gray-600">Recuperar este samará </p>
            </div>
            <x-primary-button x-data=""
              x-on:click.prevent="$dispatch('open-modal', 'confirm-rectorado-recover')">
              Recuperar
            </x-primary-button>
            <x-modal name="confirm-rectorado-recover">
              <div>
                <form method="post" class="p-6"
                  action="{{ route('samaras.unarchive', ['samara' => $samara->id]) }}">
                  @csrf
                  @method('patch')
                  <h2 class="title">
                    {{ __('¿Esta seguro que quiere recuperar este Samará?') }}
                  </h2>
                  <p class="text-secondary">
                    {{ __('Ingrese su contraseña para confirmar que desea recuperar el samara') }}
                  </p>
                  <div class="mt-6">
                    <x-input-label for="password" value="{{ __('Password') }}" class="sr-only" />
                    <x-text-input id="passwordRecuperar" name="password" type="password" class="block w-3/4 mt-1"
                      placeholder="{{ __('Password') }}" />
                    <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
                  </div>
                  <div class="flex justify-end gap-2 mt-6">
                    <x-danger-button type="button" x-on:click="$dispatch('close')" class="btn-cancel-delete">
                      {{ __('Cancelar') }}
                    </x-danger-button>
                    <x-primary-button>
                      {{ __('Recuperar samará') }}
                    </x-primary-button>
                  </div>
                </form>
              </div>
            </x-modal>
          </div>
        @endif
      </section>
    </article>
  </section>

</x-app-layout>
