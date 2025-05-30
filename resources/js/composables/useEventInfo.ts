import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { DateTime } from 'luxon';

/**
 * Composable for shared event settings:
 * - `eventDate`: Luxon DateTime|null
 * - `formattedEventDate`: string|null, formatted using provided format
 * - `eventLocationName`: string
 * - `eventLocationAddress`: string
 *
 * @param format - Luxon format string for `formattedEventDate` (defaults to "LLLL d, yyyy").
 */
export function useEventInfo(format: string = 'LLLL d, yyyy') {
  const page = usePage<{
    event_date: string | null;
    event_location_name: string;
    event_location_address: string;
  }>();

  // raw luxon DateTime or null
  const eventDate = computed<DateTime | null>(() => {
    const raw = page.props.event_date;
    return raw ? DateTime.fromISO(raw, { setZone: true }) : null;
  });

  // DateTime to a specified format
  const formattedEventDate = computed<string | null>(() => {
    return eventDate.value ? eventDate.value.toFormat(format) : null;
  });

  const eventLocationName = computed(() => page.props.event_location_name);

  const eventLocationAddress = computed(() => page.props.event_location_address);

  return {
    eventDate,
    formattedEventDate,
    eventLocationName,
    eventLocationAddress
  };
}
