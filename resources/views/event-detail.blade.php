<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            イベント詳細
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">

                <div class="max-w-2xl py-4 mx-auto">
                    <x-validation-errors class="mb-4" />

                    @if (session('status'))
                        <div class="mb-4 font-medium text-sm text-green-600">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('events.reserve', ['id' => $event->id]) }}">
                        @csrf

                        <div>
                            <x-label for="event_name" value="イベント名" />
                            {{ $event->name }}
                        </div>

                        <div class="mt-4">
                            <x-label for="information" value="イベント詳細" />
                            {!! nl2br(e($event->information)) !!}
                        </div>

                        <div class="md:flex justify-between">
                            <div class="mt-4">
                                <x-label for="event_date" value="イベント日付" />
                                {{ $event->eventDate }}
                            </div>

                            <div class="mt-4">
                                <x-label for="start_time" value="開始時間" />
                                {{ $event->startTime }}
                            </div>

                            <div class="mt-4">
                                <x-label for="end_time" value="終了時間" />
                                {{ $event->endTime }}
                            </div>
                        </div>
                        <div class="md:flex justify-between items-end">
                            <div class="mt-4">
                                <x-label for="max_people" value="定員" />
                                {{ $event->max_people }}
                            </div>
                            <div class="mt-4">
                                @if ($reservablePeople <= 0)
                                    <span class="text-red-500 text-xs">このイベントは満員です。</span>
                                @else
                                    <x-label for="reserved_people" value="予約人数" />
                                    <select name="reserved_people">
                                        @for ($i = 1; $i <= $reservablePeople; $i++)
                                            <option value="{{ $i }}">{{ $i }}</option>
                                        @endfor
                                    </select>
                                @endif

                            </div>

                            @if ($isReserved === null)
                                <input type="hidden" name="id" value="{{ $event->id }}">
                                <div class="flex items-center justify-end mt-4">
                                    @if ($reservablePeople > 0)
                                        <x-button class="ml-4">
                                            予約する
                                        </x-button>
                                    @endif
                                </div>
                            @else
                                <span class="text-xs">このイベントは既に予約済みです。</span>
                            @endif

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    {{-- <div class="py-4">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
              <div class="max-w-2xl py-4 mx-auto">
                  @if (!$users->isEmpty())
                      予約状況
                      <table class="table-auto w-full text-left whitespace-no-wrap">
                          <thead>
                              <tr>
                                  <th
                                      class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100 rounded-tl rounded-bl">
                                      予約者名</th>
                                  <th
                                      class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">
                                      予約人数</th>
                              </tr>
                          </thead>
                          <tbody>
                              @foreach ($reservations as $reservation)
                                  @if (is_null($reservation['canceled_date']))
                                      <tr>
                                          <td class="px-4 py-3">{{ $reservation['name'] }}</td>
                                          <td class="px-4 py-3">{{ $reservation['number_of_people'] }}</td>
                                      </tr>
                                  @endif
                              @endforeach
                          </tbody>
                      </table>
                  @endif
              </div>
          </div>
      </div>
  </div> --}}

</x-app-layout>
