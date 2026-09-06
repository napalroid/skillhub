import React, { useState, useMemo } from 'react';

export default function BookingCalendar({ 
  serviceId, 
  availableSlots = [], 
  onSlotSelect,
  selectedSlotId = null 
}) {
  const [currentMonth, setCurrentMonth] = useState(new Date());
  const [selectedDate, setSelectedDate] = useState(null);

  const monthNames = [
    'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
    'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
  ];

  const dayNames = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];

  const getDaysInMonth = (date) => {
    const year = date.getFullYear();
    const month = date.getMonth();
    const firstDay = new Date(year, month, 1);
    const lastDay = new Date(year, month + 1, 0);
    const daysInMonth = lastDay.getDate();
    const startingDayOfWeek = firstDay.getDay();

    return { daysInMonth, startingDayOfWeek, year, month };
  };

  const { daysInMonth, startingDayOfWeek, year, month } = getDaysInMonth(currentMonth);

  const availableDates = useMemo(() => {
    return new Set(
      availableSlots.map(slot => {
        const date = new Date(slot.date);
        return `${date.getFullYear()}-${String(date.getMonth() + 1).padStart(2, '0')}-${String(date.getDate()).padStart(2, '0')}`;
      })
    );
  }, [availableSlots]);

  const slotsForSelectedDate = useMemo(() => {
    if (!selectedDate) return [];
    
    const selectedDateStr = `${selectedDate.getFullYear()}-${String(selectedDate.getMonth() + 1).padStart(2, '0')}-${String(selectedDate.getDate()).padStart(2, '0')}`;
    
    return availableSlots.filter(slot => {
      const slotDate = new Date(slot.date);
      const slotDateStr = `${slotDate.getFullYear()}-${String(slotDate.getMonth() + 1).padStart(2, '0')}-${String(slotDate.getDate()).padStart(2, '0')}`;
      return slotDateStr === selectedDateStr;
    }).sort((a, b) => {
      return a.time_start.localeCompare(b.time_start);
    });
  }, [selectedDate, availableSlots]);

  const groupedSlots = useMemo(() => {
    const groups = {
      morning: [],
      afternoon: [],
      evening: []
    };

    slotsForSelectedDate.forEach(slot => {
      const hour = parseInt(slot.time_start.split(':')[0]);
      if (hour < 12) {
        groups.morning.push(slot);
      } else if (hour < 17) {
        groups.afternoon.push(slot);
      } else {
        groups.evening.push(slot);
      }
    });

    return groups;
  }, [slotsForSelectedDate]);

  const handlePrevMonth = () => {
    setCurrentMonth(new Date(currentMonth.getFullYear(), currentMonth.getMonth() - 1));
  };

  const handleNextMonth = () => {
    setCurrentMonth(new Date(currentMonth.getFullYear(), currentMonth.getMonth() + 1));
  };

    const handleDateClick = (day) => {
      const date = new Date(year, month, day);
      const dateStr = `${date.getFullYear()}-${String(date.getMonth() + 1).padStart(2, '0')}-${String(date.getDate()).padStart(2, '0')}`;
      
      if (availableDates.has(dateStr)) {
        setSelectedDate(date);
        
        // Dispatch event untuk listener lain (seperti seller manage page)
        window.dispatchEvent(new CustomEvent('date-selected', {
          detail: { date: dateStr }
        }));
      }
    };

    const handleSlotClick = (slot) => {
      if (onSlotSelect) {
        onSlotSelect(slot.id);
      }
      
      // Dispatch event dengan slot data untuk listener lain
      window.dispatchEvent(new CustomEvent('slot-selected', {
        detail: { 
          date: slot.date,
          slot: slot
        }
      }));
    };

  const isDateAvailable = (day) => {
    const date = new Date(year, month, day);
    const dateStr = `${date.getFullYear()}-${String(date.getMonth() + 1).padStart(2, '0')}-${String(date.getDate()).padStart(2, '0')}`;
    return availableDates.has(dateStr);
  };

  const isDateSelected = (day) => {
    if (!selectedDate) return false;
    return selectedDate.getDate() === day && 
           selectedDate.getMonth() === month && 
           selectedDate.getFullYear() === year;
  };

  const isToday = (day) => {
    const today = new Date();
    return today.getDate() === day && 
           today.getMonth() === month && 
           today.getFullYear() === year;
  };

  const formatSelectedDate = () => {
    if (!selectedDate) return '';
    const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
    const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    return `${days[selectedDate.getDay()]}, ${selectedDate.getDate()} ${months[selectedDate.getMonth()]}`;
  };

  const renderSlotGroup = (title, subtitle, slots) => {
    if (slots.length === 0) return null;

    const selectedSlot = slots.find(s => s.id === selectedSlotId);

    return (
      <div className="mb-6">
        <div className="mb-3">
          <h4 className="text-xs font-bold uppercase tracking-wider text-white">{title}</h4>
          <p className="text-xs text-gray-400">{subtitle}</p>
        </div>
        
        <div className="grid grid-cols-3 gap-3 sm:grid-cols-4 lg:grid-cols-5">
            {slots.map(slot => {
              const isSelected = selectedSlotId === slot.id;
              const isAvailable = true;

             return (
               <button
                 key={slot.id}
                 type="button"
                 onClick={() => handleSlotClick(slot)}
                 disabled={!isAvailable}
                 style={{
                  transform: isSelected ? 'scale(1.1)' : undefined,
                  boxShadow: isSelected ? '0 25px 50px -12px rgba(255, 255, 255, 0.25)' : undefined,
                  borderWidth: isSelected ? '2px' : '1px',
                  zIndex: isSelected ? 10 : undefined,
                  position: 'relative'
                }}
                className={`
                  rounded-lg px-3 py-2.5 text-sm font-bold transition-all duration-300
                  ${isSelected
                    ? 'border-white bg-white text-black ring-4 ring-white/30'
                    : isAvailable
                      ? 'border-gray-700 bg-transparent text-white hover:border-gray-500 hover:bg-gray-900'
                      : 'border-gray-800 bg-gray-900 text-gray-600 cursor-not-allowed opacity-50'
                  }
                `}
              >
                {isSelected && (
                  <span className="inline-block mr-1 text-green-600">
                    <svg className="w-3.5 h-3.5 inline" fill="currentColor" viewBox="0 0 20 20">
                      <path fillRule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clipRule="evenodd" />
                    </svg>
                  </span>
                )}
                {slot.time_start}
              </button>
            );
          })}
        </div>
      </div>
    );
  };

  const calendarDays = [];
  for (let i = 0; i < startingDayOfWeek; i++) {
    calendarDays.push(<div key={`empty-${i}`} className="aspect-square" />);
  }
  for (let day = 1; day <= daysInMonth; day++) {
    calendarDays.push(
      <button
        key={day}
        type="button"
        onClick={() => handleDateClick(day)}
        disabled={!isDateAvailable(day)}
        className={`
          aspect-square flex items-center justify-center text-sm font-medium transition
          ${isDateSelected(day)
            ? 'bg-white text-black'
            : isDateAvailable(day)
              ? 'text-white hover:bg-gray-800'
              : 'text-gray-700 cursor-not-allowed'
          }
          ${isToday(day) && !isDateSelected(day) ? 'ring-1 ring-gray-600' : ''}
        `}
      >
        {day}
      </button>
    );
  }

  return (
    <div className="grid gap-8 lg:grid-cols-[400px_1fr]">
      <div className="rounded-xl border border-gray-800 bg-black p-6">
        <div className="mb-6 flex items-center justify-between">
          <h3 className="text-base font-bold text-white">
            {monthNames[month]} {year}
          </h3>
          <div className="flex gap-2">
            <button
              type="button"
              onClick={handlePrevMonth}
              className="flex h-8 w-8 items-center justify-center rounded-lg border border-gray-700 text-white transition hover:bg-gray-900"
            >
              ←
            </button>
            <button
              type="button"
              onClick={handleNextMonth}
              className="flex h-8 w-8 items-center justify-center rounded-lg border border-gray-700 text-white transition hover:bg-gray-900"
            >
              →
            </button>
          </div>
        </div>

        <div className="mb-3 grid grid-cols-7 gap-2">
          {dayNames.map(day => (
            <div key={day} className="text-center text-xs font-medium text-gray-500">
              {day}
            </div>
          ))}
        </div>

        <div className="grid grid-cols-7 gap-2">
          {calendarDays}
        </div>
      </div>

      <div className="rounded-xl border border-gray-800 bg-black p-6">
        {selectedDate ? (
          <>
            <h3 className="mb-6 text-lg font-bold text-white">{formatSelectedDate()}</h3>
            
            {slotsForSelectedDate.length === 0 ? (
              <div className="flex h-64 items-center justify-center">
                <p className="text-sm text-gray-500">Tidak ada slot tersedia</p>
              </div>
            ) : (
              <>
                {renderSlotGroup('Pagi', '9:00 – 12:00', groupedSlots.morning)}
                {renderSlotGroup('Siang', '12:00 – 17:00', groupedSlots.afternoon)}
                {renderSlotGroup('Sore/Malam', '17:00 – 21:00', groupedSlots.evening)}
              </>
            )}
          </>
        ) : (
          <div className="flex h-64 items-center justify-center">
            <p className="text-sm text-gray-400">Pilih tanggal untuk melihat slot waktu</p>
          </div>
        )}
      </div>
    </div>
  );
}
