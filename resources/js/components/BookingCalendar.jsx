import React, { useEffect, useMemo, useState } from 'react';

const dayNames = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];
const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];

function toLocalDate(value) {
  return new Date(`${value}T00:00:00`);
}

export default function BookingCalendar({ availableSlots = [], onSlotSelect, selectedSlotId = null }) {
  const availableDates = useMemo(() => [...new Set(availableSlots.map((slot) => slot.date))], [availableSlots]);
  const [visibleStart, setVisibleStart] = useState(0);
  const [selectedDate, setSelectedDate] = useState(availableDates[0] ?? null);

  useEffect(() => {
    if (!availableDates.includes(selectedDate)) setSelectedDate(availableDates[0] ?? null);
  }, [availableDates, selectedDate]);

  const dates = availableDates.slice(visibleStart, visibleStart + 5);
  const slots = useMemo(() => availableSlots
    .filter((slot) => slot.date === selectedDate && Number(slot.available_count) > 0)
    .sort((a, b) => a.time_start.localeCompare(b.time_start)), [availableSlots, selectedDate]);

  const chooseDate = (date) => {
    setSelectedDate(date);
    const stillAvailable = availableSlots.some((slot) => slot.id === selectedSlotId && slot.date === date && Number(slot.available_count) > 0);
    if (!stillAvailable) onSlotSelect?.(null);
  };

  if (!availableDates.length) return null;

  return (
    <section className="booking-slots" aria-labelledby="booking-slots-title">
      <div className="booking-slots__heading">
        <span className="booking-icon" aria-hidden="true">◫</span>
        <div><h2 id="booking-slots-title">Booking Slots</h2><p>Pilih tanggal dan waktu yang sesuai dengan kamu.</p></div>
      </div>

      <div className="booking-date-nav" aria-label="Pilih tanggal booking">
        <button type="button" className="booking-nav-button" aria-label="Tanggal sebelumnya" disabled={visibleStart === 0} onClick={() => setVisibleStart((value) => Math.max(0, value - 1))}>‹</button>
        <div className="booking-date-nav__label">{selectedDate && `${dayNames[toLocalDate(selectedDate).getDay()]}, ${toLocalDate(selectedDate).getDate()} ${monthNames[toLocalDate(selectedDate).getMonth()]} ${toLocalDate(selectedDate).getFullYear()}`}</div>
        <button type="button" className="booking-nav-button" aria-label="Tanggal berikutnya" disabled={visibleStart + 5 >= availableDates.length} onClick={() => setVisibleStart((value) => Math.min(availableDates.length - 1, value + 1))}>›</button>
      </div>

      <div className="booking-dates" role="group" aria-label="Tanggal tersedia">
        {dates.map((date) => {
          const parsed = toLocalDate(date);
          const active = date === selectedDate;
          return <button key={date} type="button" onClick={() => chooseDate(date)} className={`booking-date ${active ? 'is-selected' : ''}`} aria-pressed={active}><span>{dayNames[parsed.getDay()]}</span><strong>{parsed.getDate()} {monthNames[parsed.getMonth()]}</strong></button>;
        })}
      </div>

      <p className="booking-time-label">Pilih waktu</p>
      {slots.length ? <div className="booking-times" role="group" aria-label="Waktu tersedia">
        {slots.map((slot) => {
          const selected = Number(selectedSlotId) === Number(slot.id);
          return <button key={slot.id} type="button" onClick={() => onSlotSelect?.(slot.id)} className={`booking-time ${selected ? 'is-selected' : ''}`} aria-pressed={selected}>{slot.time_start}</button>;
        })}
      </div> : <p className="booking-empty">Tidak ada waktu tersedia pada tanggal ini.</p>}
    </section>
  );
}
