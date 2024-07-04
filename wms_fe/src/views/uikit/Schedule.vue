<script>
import { ref } from 'vue';
import FullCalendar from '@fullcalendar/vue3';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import interactionPlugin from '@fullcalendar/interaction';

export default {
    name: 'ScheduleComponent',
    components: {
        FullCalendar
    },
    setup() {
        const events = ref([
            {
                title: 'Event 1',
                start: '2024-06-19T10:00:00',
                end: '2024-06-19T12:00:00'
            },
            {
                title: 'Event 2',
                start: '2024-06-20T14:00:00',
                end: '2024-06-20T15:00:00'
            }
        ]);

        const calendarOptions = ref({
            plugins: [dayGridPlugin, timeGridPlugin, interactionPlugin],
            initialView: 'dayGridMonth',
            editable: true,
            selectable: true,
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            }
        });

        return {
            events,
            calendarOptions
        };
    }
};
</script>
<template>
    <div>
        <h2>Schedule Calendar</h2>
        <FullCalendar :events="events" :options="calendarOptions" />
    </div>
</template>

<style scoped>
/* Add custom styles here if needed */
</style>
