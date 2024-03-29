<x-datatables.styles />

<x-app-layout>
  <x-slot name="header">
    <div class="flex items-center gap-1 pt-1">
      <x-nav-link href="{{ route('formato-911') }}">
        Formato 911
      </x-nav-link>
      <x-arrow />
      <x-nav-link href="{{ route('personal-docente-antiguedad.index') }}" :active="true">
        Personal Docente por Antiguedad
      </x-nav-link>
    </div>
  </x-slot>

  <section class="flex flex-col w-full pt-10 pb-32 lg:px-20">
    @if (Auth::check() && Auth::user()->role->role == 'Administrador')
      <section class="flex flex-col items-end w-full gap-1 px-3 md:flex-row md:justify-end">
        <article>
          <x-secondary-button x-data="" x-on:click.prevent="$dispatch('open-modal', 'import')">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
              stroke="currentColor" class="w-4 h-4">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m6.75 12l-3-3m0 0l-3 3m3-3v6m-1.5-15H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
            </svg>
            {{ __('Importar datos') }}
          </x-secondary-button>
          <x-modal name="import">
            <div class="flex flex-col gap-4 px-4 py-8">
              <div class="flex flex-col gap-2 mb-3">
                <h2 class="title">Importar nuevos Personales Docentes</h2>
                <p class="text-secondary">
                  Seleccion el archivo con la informacion que desea importar,
                  por favor asegurese que la estructura de
                  los datos esta de manera correcta, en caso de no saber cual es la estructura correspondiente descargue
                  este <a href="{{ route('formato.importacion', ['name' => 'formato-personal-docente_antiguedad']) }}"
                    class="underline">archivo.</a>.
                </p>
              </div>
              <form action="{{ route('personal-docente-antiguedad.import') }}" method="post"
                enctype="multipart/form-data">
                @csrf
                <div class="flex flex-col justify-center w-full">
                  <label class="block">
                    <span class="sr-only">Elige un archivo.</span>
                    <input type="file"
                      class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-500 file:text-white hover:file:bg-blue-600 "
                      name="file" />
                  </label>
                  <div class="flex items-center justify-end gap-2 mt-4">
                    <x-danger-button x-on:click="$dispatch('close')" type="button">Cancelar</x-danger-button>
                    <x-primary-button class="gap-2" x-data="{ loading: false }" x-on:click="loading = true">
                      <span>Importar</span>
                      <span x-show="loading">
                        <x-loaders.spinner />
                      </span>
                    </x-primary-button>
                  </div>
                </div>
              </form>
            </div>
          </x-modal>
        </article>

        <article>
          <x-primary-button x-data="" x-on:click.prevent="$dispatch('open-modal', 'create')">
            {{ __('Nuevo Personal Docente') }}
          </x-primary-button>
          <x-modal name="create">
            <div class="px-4 py-8">
              <h2 class="title">Crear nuevo Personal docente</h2>
              <form method="post" action="{{ route('personal-docente-antiguedad.store') }}">
                @csrf
                @method('post')

                <div class="flex flex-col gap-1 mt-5">
                  <x-input-label for="unidad_academica" :value="__('Unidad Academica')" />
                  <select name="unidad_academica" id="unidad_academica" class="border border-gray-300 rounded">
                    <option value="">Selecciona una unidad academica</option>
                    @foreach ($unidadesAcademicas as $unidadAcademica)
                      <option value="{{ $unidadAcademica->id }}">
                        {{ $unidadAcademica->unidadDependencia->unidad_dependencia }}</option>
                    @endforeach
                  </select>
                  <x-input-error class="mt-2" :messages="$errors->get('unidad_academica')" />
                </div>

                <div class="flex flex-col gap-1 mt-3">
                  <x-input-label for="anio" :value="__('Año')" />
                  <x-text-input id="anio" name="anio" type="text" class="block w-1/4 mt-1"
                    placeholder="{{ __('0000') }}" />
                  <x-input-error class="mt-1" :messages="$errors->get('anio')" />
                </div>

                <div class="flex flex-col gap-1 mt-3">
                  <x-input-label for="grupo_antiguedad" :value="__('Grupo de antiguedad')" />
                  <select name="grupo_antiguedad" id="grupo_antiguedad" class="border border-gray-300 rounded w-fit">
                    <option value="">Selecciona un grupo de antiguedad</option>
                    @foreach ($grupoAntiguedad as $item)
                      <option value="{{ $item->id }}">
                        {{ $item->grupo }}</option>
                    @endforeach
                  </select>
                  <x-input-error class="mt-2" :messages="$errors->get('unidad_academica')" />
                </div>

                <x-forms.input-data nameHombres="hombres" nameMujeres="mujeres" name="Personal" />

                <div class="flex justify-end gap-2 pb-2 mt-10 text-gray-100">
                  <x-danger-button x-on:click="$dispatch('close')" type="button">
                    {{ __('Cancelar') }}
                  </x-danger-button>
                  <x-primary-button class="gap-2" x-data="{ loading: false }" x-on:click="loading = true">
                    <span>Crear</span>
                    <span x-show="loading">
                      <x-loaders.spinner />
                    </span>
                  </x-primary-button>
                </div>
              </form>
            </div>
          </x-modal>
        </article>
      </section>
    @endif

    <section class="w-full h-10 px-3 mt-1 text-green-900">
      @if ($message = Session::get('success'))
        <x-alerts.success :text="$message" />
      @elseif ($message = Session::get('warning'))
        <x-alerts.warning :text="$message" />
      @endif
    </section>

    <section class="mt-1 card-container">
      <table class="table stripe" id="personalDocenteAntiguedad" style="width: 100%">
        <thead class="text-sm bg-gray-900 text-gray-50">
          <tr>
            <th>Unidad academica</th>
            <th>Tipo unidad academica</th>
            <th>Municipio</th>
            <th>Campus</th>
            <th>Año</th>
            <th>Grupo</th>
            <th>Total</th>
            <th>Opciones</th>
          </tr>
        </thead>
        <tbody class="text">
          @foreach ($personalDocente as $item)
            <tr class="{{ $item->status ? '' : 'opacity-40' }}">
              <td>{{ $item->unidadAcademica->unidadDependencia->unidad_dependencia }}</td>
              <td>{{ $item->unidadAcademica->tipoUnidadAcademica->tipo }}</td>
              <td>{{ $item->unidadAcademica->municipio->municipio }}</td>
              <td>{{ $item->unidadAcademica->municipio->campus->campus }}</td>
              <td>{{ $item->anio }}</td>
              <td>{{ $item->antiguedadGrupo->grupo }}</td>
              <td>{{ $item->total }}</td>
              <td>
                <div class="flex gap-2">
                  <a href="{{ route('personal-docente-antiguedad.show', ['personal_docente_antiguedad' => $item->id]) }}"
                    class="btn-primary">Ver</a>
                  @if (Auth::check() && Auth::user()->role->role == 'Administrador')
                    <a href="{{ route('personal-docente-antiguedad.edit', ['personal_docente_antiguedad' => $item->id]) }}"
                      class="btn-secondary">Editar</a>
                  @endif
                </div>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </section>

  </section>
</x-app-layout>

<x-datatables.scripts />
<script src="{{ asset('js/dataTableConfig.js') }}"></script>
<script>
  $(document).ready(datatable({
    id: '#personalDocenteAntiguedad',
    props: {
      orderBy: [
        [4, 'desc'],
        [5, 'asc']
      ],
      scroll: 'true',
      fileName: 'Personal docente por grupo de antiguedad',
      columns: [0, 1, 2, 3, 4, 5, 6]
    }
  }))
</script>
