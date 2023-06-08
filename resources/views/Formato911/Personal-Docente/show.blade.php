<x-datatables.styles />

<x-app-layout>
  <x-slot name="header">
    <div class="flex items-center gap-1 pt-1">
      <x-nav-link href="{{ route('formato-911') }}">
        Formato 911
      </x-nav-link>
      <x-arrow />
      <x-nav-link href="{{ route('personal-docente.index') }}">
        Personal Docente
      </x-nav-link>
      <x-arrow />
      <x-nav-link :active="true">
        {{ $personalDocente->unidadAcademica->unidadDependencia->unidad_dependencia }}
      </x-nav-link>
    </div>
  </x-slot>

  <section class="flex flex-col w-full pt-10 pb-32 lg:px-20">

    <section class="card-container ">
      <div class="flex flex-col gap-3">
        <div class="flex flex-col gap-1">
          <div class="flex items-center gap-1">
            <h3 class="title">
              {{ $personalDocente->unidadAcademica->unidadDependencia->unidad_dependencia }}
            </h3>
            @if (Auth::check() && Auth::user()->role->role == 'Administrador')
              <a href="{{ route('personal-docente.edit', ['personal_docente' => $personalDocente->id]) }}"
                class="px-2 py-1 text-xs transition bg-gray-300 rounded hover:bg-gray-200 hover:text-gray-800">
                Editar Personal
              </a>
            @endif
          </div>
          <p class="mt-2 text">Tipo de plantel:
            {{ $personalDocente->unidadAcademica->tipoUnidadAcademica->tipo }}</p>
          <p class="text">{{ $personalDocente->unidadAcademica->municipio->municipio }}</p>
          <p class="text">Campus: {{ $personalDocente->unidadAcademica->municipio->campus->campus }}
          </p>
        </div>
      </div>

      <div class="mt-12">
        <table class="table" id="personalDocente">
          <thead class="text-sm bg-gray-900 text-gray-50">
            <tr>
              <th>Año</th>
              <th>PITC Hombres</th>
              <th>PITC Mujeres</th>
              <th>PITC Total</th>
              <th>P34T Hombres</th>
              <th>P34T Mujeres</th>
              <th>P34T Total</th>
              <th>PMT Hombres</th>
              <th>PMT Mmujeres</th>
              <th>PMT Total</th>
              <th>PPH Hombres</th>
              <th>PPH Mujeres</th>
              <th>PPH Total</th>
            </tr>
          </thead>
          <tbody class="text">
            <tr>
              <td>{{ $personalDocente->anio }}</td>
              <td>{{ $personalDocente->pitc_h }}</td>
              <td>{{ $personalDocente->pitc_m }}</td>
              <td>{{ $personalDocente->pitc_t }}</td>
              <td>{{ $personalDocente->p34t_h }}</td>
              <td>{{ $personalDocente->p34t_m }}</td>
              <td>{{ $personalDocente->p34t_t }}</td>
              <td>{{ $personalDocente->pmt_h }}</td>
              <td>{{ $personalDocente->pmt_m }}</td>
              <td>{{ $personalDocente->pmt_t }}</td>
              <td>{{ $personalDocente->pph_h }}</td>
              <td>{{ $personalDocente->pph_m }}</td>
              <td>{{ $personalDocente->pph_t }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </section>

  </section>
</x-app-layout>

<x-datatables.scripts />
<script src="{{ asset('js/dataTableConfig.js') }}"></script>
<script>
  $(document).ready(datatable({
    id: '#personalDocente',
    props: {
      orderBy: [4, 'desc'],
      scroll: 'true',
      fileName: '{{ $personalDocente->unidadAcademica->unidadDependencia->unidad_dependencia }} - Personal docente - {{ $personalDocente->anio }}',
      columns: [0, 1, 2, 3, 4, 5, 6, 7, 8]
    }
  }))
</script>
