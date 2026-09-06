import React from 'react';
import { createRoot } from 'react-dom/client';
import BookingCalendar from './components/BookingCalendar';

document.addEventListener('DOMContentLoaded', () => {
  const container = document.getElementById('booking-calendar-root');
  
  if (container) {
    const serviceId = container.dataset.serviceId;
    const availableSlots = JSON.parse(container.dataset.availableSlots || '[]');
    const selectedSlotInput = document.getElementById('selected_slot_id');

    const root = createRoot(container);
    
    root.render(
      <BookingCalendar
        serviceId={serviceId}
        availableSlots={availableSlots}
        selectedSlotId={selectedSlotInput?.value || null}
        onSlotSelect={(slotId) => {
          if (selectedSlotInput) {
            selectedSlotInput.value = slotId || '';
            selectedSlotInput.dispatchEvent(new Event('change', { bubbles: true }));
            selectedSlotInput.dispatchEvent(new Event('input', { bubbles: true }));
          }
          
          if (window.updateSubmitButton) {
            window.updateSubmitButton(slotId);
          }
          
          console.log('Slot selected:', slotId);
        }}
      />
    );
  }
});
