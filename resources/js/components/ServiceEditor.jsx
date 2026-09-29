import React, { useState, useEffect } from 'react';
import { motion, AnimatePresence } from 'framer-motion';
import { IconArrowLeft, IconDeviceFloppy, IconX, IconChevronUp, IconChevronDown, IconTrash, IconPlus, IconCalendarTime, IconTemplate, IconUpload } from '@tabler/icons-react';

const transition = { type: "spring", stiffness: 400, damping: 30 };
const staggerContainer = { hidden: { opacity: 0 }, show: { opacity: 1, transition: { staggerChildren: 0.05 } } };
const staggerItem = { hidden: { opacity: 0, y: 20 }, show: { opacity: 1, y: 0 } };

export const ServiceEditor = ({ 
  service, 
  categories, 
  subcategories,
  templates,
  saveRoute,
  myServicesRoute 
}) => {
  const [activeTab, setActiveTab] = useState('informasi');
  const [formData, setFormData] = useState({
    title: service?.title || '',
    category_id: service?.subcategory?.category_id || '',
    subcategory_id: service?.subcategory_id || '',
    price: service?.price || '',
    description: service?.description || '',
    image: null
  });
  const [fields, setFields] = useState(service?.booking_config?.fields || []);
  const [timeSlotsEnabled, setTimeSlotsEnabled] = useState(service?.time_slots_enabled || false);
  const [newField, setNewField] = useState({
    label: '',
    type: 'text',
    required: false,
    placeholder: '',
    options: ''
  });
  const [showTemplateModal, setShowTemplateModal] = useState(false);
  const [previewImage, setPreviewImage] = useState(null);

  const filteredSubcategories = subcategories.filter(
    sub => sub.category_id == formData.category_id
  );

  const handleImageChange = (e) => {
    const file = e.target.files[0];
    if (file) {
      setFormData({ ...formData, image: file });
      setPreviewImage(URL.createObjectURL(file));
    }
  };

  const addField = () => {
    if (!newField.label.trim()) return;
    
    const field = {
      name: newField.label.toLowerCase().replace(/\s+/g, '_'),
      label: newField.label,
      type: newField.type,
      required: newField.required,
      placeholder: newField.placeholder || '',
      options: newField.type in ['select', 'radio', 'checkbox'] 
        ? newField.options.split(',').map(o => o.trim()).filter(Boolean)
        : []
    };
    
    setFields([...fields, field]);
    setNewField({ label: '', type: 'text', required: false, placeholder: '', options: '' });
  };

  const removeField = (index) => {
    setFields(fields.filter((_, i) => i !== index));
  };

  const moveField = (index, direction) => {
    const newFields = [...fields];
    if (direction === 'up' && index > 0) {
      [newFields[index - 1], newFields[index]] = [newFields[index], newFields[index - 1]];
    } else if (direction === 'down' && index < newFields.length - 1) {
      [newFields[index + 1], newFields[index]] = [newFields[index], newFields[index + 1]];
    }
    setFields(newFields);
  };

  const applyTemplate = (templateKey) => {
    const template = templates[templateKey];
    if (template) {
      setFields([...fields, ...template.fields]);
      setShowTemplateModal(false);
    }
  };

  return (
    <div className="min-h-screen bg-gradient-to-br from-slate-50 via-white to-blue-50/30">
      <div className="mx-auto max-w-7xl px-6 lg:px-8 py-8 lg:py-12">
        
        <motion.header 
          initial={{ opacity: 0, y: -20 }}
          animate={{ opacity: 1, y: 0 }}
          transition={transition}
          className="mb-10"
        >
          <a 
            href={myServicesRoute}
            className="inline-flex items-center gap-2 text-sm font-medium text-slate-500 hover:text-slate-900 transition-colors mb-6 group"
          >
            <IconArrowLeft className="w-4 h-4 group-hover:-translate-x-1 transition-transform" />
            Kembali ke jasa saya
          </a>
          
          <div className="flex items-end justify-between gap-6">
            <div>
              <motion.div 
                initial={{ scale: 0.9, opacity: 0 }}
                animate={{ scale: 1, opacity: 1 }}
                transition={{ ...transition, delay: 0.1 }}
                className="inline-flex items-center gap-2 px-3 py-1.5 bg-blue-100 text-blue-700 text-xs font-semibold rounded-full mb-4"
              >
                <span className="w-1.5 h-1.5 bg-blue-500 rounded-full animate-pulse" />
                Service Editor
              </motion.div>
              <h1 className="text-3xl lg:text-4xl font-bold text-slate-900 tracking-tight">
                Edit Jasa
              </h1>
              <p className="mt-2 text-slate-500 max-w-lg">
                Perbarui informasi jasa yang kamu tawarkan di SkillHub
              </p>
            </div>
          </div>
        </motion.header>

        <div className="flex flex-col lg:flex-row gap-8">
          
          <motion.aside 
            initial={{ opacity: 0, x: -20 }}
            animate={{ opacity: 1, x: 0 }}
            transition={{ ...transition, delay: 0.2 }}
            className="lg:w-72 shrink-0"
          >
            <div className="lg:sticky lg:top-8 space-y-2">
              {[
                { id: 'informasi', label: 'Informasi Dasar', desc: 'Detail utama jasa', icon: '01' },
                { id: 'booking-config', label: 'Booking Config', desc: 'Field untuk buyer', icon: '02' }
              ].map((tab, index) => (
                <motion.button
                  key={tab.id}
                  initial={{ opacity: 0, x: -20 }}
                  animate={{ opacity: 1, x: 0 }}
                  transition={{ ...transition, delay: 0.3 + index * 0.1 }}
                  onClick={() => setActiveTab(tab.id)}
                  className={`w-full text-left p-4 rounded-xl transition-all duration-200 group ${
                    activeTab === tab.id
                      ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/25'
                      : 'bg-white hover:bg-slate-50 text-slate-900 border border-slate-200'
                  }`}
                >
                  <div className="flex items-center gap-3">
                    <span className={`flex items-center justify-center w-8 h-8 rounded-lg text-xs font-bold ${
                      activeTab === tab.id
                        ? 'bg-white/20 text-white'
                        : 'bg-slate-100 text-slate-500 group-hover:bg-blue-100 group-hover:text-blue-600'
                    } transition-colors`}>
                      {tab.icon}
                    </span>
                    <div>
                      <div className="font-semibold text-sm">{tab.label}</div>
                      <div className={`text-xs mt-0.5 ${
                        activeTab === tab.id ? 'text-blue-100' : 'text-slate-400'
                      }`}>
                        {tab.desc}
                      </div>
                    </div>
                  </div>
                </motion.button>
              ))}
            </div>
          </motion.aside>

          <motion.main 
            initial={{ opacity: 0, y: 20 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ ...transition, delay: 0.3 }}
            className="flex-1 min-w-0"
          >
            <AnimatePresence mode="wait">
              {activeTab === 'informasi' ? (
                <motion.div
                  key="informasi"
                  initial={{ opacity: 0, x: 20 }}
                  animate={{ opacity: 1, x: 0 }}
                  exit={{ opacity: 0, x: -20 }}
                  transition={transition}
                  className="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden"
                >
                  <form 
                    method="POST" 
                    action={saveRoute} 
                    encType="multipart/form-data"
                    id="service-form"
                  >
                    <input type="hidden" name="_token" value={document.querySelector('meta[name="csrf-token"]')?.content} />
                    <input type="hidden" name="_method" value="PUT" />
                    
                    <div className="p-6 lg:p-8 space-y-6">
                      <motion.div variants={staggerItem} initial="hidden" animate="show">
                        <label className="block text-sm font-semibold text-slate-700 mb-2">
                          Nama Jasa
                        </label>
                        <input
                          type="text"
                          name="title"
                          value={formData.title}
                          onChange={(e) => setFormData({ ...formData, title: e.target.value })}
                          required
                          maxLength={255}
                          placeholder="Contoh: Desain poster acara sekolah"
                          className="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                        />
                      </motion.div>

                      <motion.div variants={staggerItem} initial="hidden" animate="show" className="grid sm:grid-cols-2 gap-6">
                        <div>
                          <label className="block text-sm font-semibold text-slate-700 mb-2">
                            Kategori
                          </label>
                          <select
                            name="category_id"
                            value={formData.category_id}
                            onChange={(e) => setFormData({ 
                              ...formData, 
                              category_id: e.target.value,
                              subcategory_id: ''
                            })}
                            required
                            className="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all cursor-pointer"
                          >
                            <option value="">Pilih kategori</option>
                            {categories.map(cat => (
                              <option key={cat.id} value={cat.id}>{cat.name}</option>
                            ))}
                          </select>
                        </div>

                        <div>
                          <label className="block text-sm font-semibold text-slate-700 mb-2">
                            Subkategori
                          </label>
                          <select
                            name="subcategory_id"
                            value={formData.subcategory_id}
                            onChange={(e) => setFormData({ ...formData, subcategory_id: e.target.value })}
                            required
                            disabled={!formData.category_id}
                            className="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all disabled:opacity-50 disabled:cursor-not-allowed cursor-pointer"
                          >
                            <option value="">Pilih subkategori</option>
                            {filteredSubcategories.map(sub => (
                              <option key={sub.id} value={sub.id}>{sub.name}</option>
                            ))}
                          </select>
                        </div>
                      </motion.div>

                      <motion.div variants={staggerItem} initial="hidden" animate="show">
                        <label className="block text-sm font-semibold text-slate-700 mb-2">
                          Harga Jasa
                        </label>
                        <div className="relative">
                          <span className="absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 font-medium">
                            Rp
                          </span>
                          <input
                            type="number"
                            name="price"
                            value={formData.price}
                            onChange={(e) => setFormData({ ...formData, price: e.target.value })}
                            required
                            min="0"
                            placeholder="250000"
                            className="w-full pl-12 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                          />
                        </div>
                      </motion.div>

                      <motion.div variants={staggerItem} initial="hidden" animate="show">
                        <label className="block text-sm font-semibold text-slate-700 mb-2">
                          Deskripsi Jasa
                        </label>
                        <textarea
                          name="description"
                          value={formData.description}
                          onChange={(e) => setFormData({ ...formData, description: e.target.value })}
                          required
                          rows={5}
                          placeholder="Jelaskan apa yang akan kamu kerjakan, hasil yang didapat, dan ketentuan jasamu..."
                          className="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all resize-none"
                        />
                      </motion.div>

                      <motion.div variants={staggerItem} initial="hidden" animate="show">
                        <label className="block text-sm font-semibold text-slate-700 mb-3">
                          Gambar
                        </label>
                        
                        {service?.image && !previewImage && (
                          <div className="mb-4 p-4 bg-slate-50 rounded-xl border border-slate-200">
                            <div className="flex items-center gap-4">
                              <div className="w-20 h-20 rounded-lg overflow-hidden bg-slate-200">
                                <img 
                                  src={`/storage/${service.image}`}
                                  alt="Current"
                                  className="w-full h-full object-cover"
                                />
                              </div>
                              <div>
                                <p className="text-sm font-medium text-slate-700">Gambar saat ini</p>
                                <p className="text-xs text-slate-500 mt-1">Upload gambar baru untuk mengganti</p>
                              </div>
                            </div>
                          </div>
                        )}

                        {previewImage && (
                          <div className="mb-4 p-4 bg-blue-50 rounded-xl border border-blue-200">
                            <div className="flex items-center gap-4">
                              <div className="w-20 h-20 rounded-lg overflow-hidden bg-slate-200">
                                <img 
                                  src={previewImage}
                                  alt="Preview"
                                  className="w-full h-full object-cover"
                                />
                              </div>
                              <div>
                                <p className="text-sm font-medium text-blue-700">Gambar baru</p>
                                <button
                                  type="button"
                                  onClick={() => {
                                    setPreviewImage(null);
                                    setFormData({ ...formData, image: null });
                                  }}
                                  className="text-xs text-blue-600 hover:text-blue-800 mt-1"
                                >
                                  Hapus
                                </button>
                              </div>
                            </div>
                          </div>
                        )}

                        <label className="group cursor-pointer block">
                          <input
                            type="file"
                            name="image"
                            accept="image/jpeg,image/png"
                            onChange={handleImageChange}
                            className="sr-only"
                          />
                          <div className="flex flex-col items-center justify-center py-8 px-4 border-2 border-dashed border-slate-300 rounded-xl hover:border-blue-400 hover:bg-blue-50/50 transition-all">
                            <IconUpload className="w-8 h-8 text-slate-400 group-hover:text-blue-500 transition-colors mb-2" />
                            <span className="text-sm text-slate-500 group-hover:text-blue-600 transition-colors">
                              JPG / PNG - Maximum 2 MB
                            </span>
                          </div>
                        </label>
                      </motion.div>
                    </div>

                    <div className="px-6 lg:px-8 py-4 bg-slate-50 border-t border-slate-200 flex items-center justify-end gap-3">
                      <a
                        href={myServicesRoute}
                        className="px-5 py-2.5 text-sm font-medium text-slate-600 hover:text-slate-900 transition-colors"
                      >
                        Batal
                      </a>
                      <button
                        type="submit"
                        className="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all shadow-lg shadow-blue-600/25 hover:shadow-xl hover:shadow-blue-600/30 hover:-translate-y-0.5"
                      >
                        <IconDeviceFloppy className="w-4 h-4" />
                        Simpan Perubahan
                      </button>
                    </div>
                  </form>
                </motion.div>
              ) : (
                <motion.div
                  key="booking-config"
                  initial={{ opacity: 0, x: 20 }}
                  animate={{ opacity: 1, x: 0 }}
                  exit={{ opacity: 0, x: -20 }}
                  transition={transition}
                  className="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden"
                >
                  <div className="p-6 lg:p-8 space-y-8">
                    
                    <motion.div 
                      variants={staggerItem}
                      initial="hidden"
                      animate="show"
                      className="p-5 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl border border-blue-100"
                    >
                      <div className="flex items-start justify-between gap-4">
                        <div className="flex items-start gap-4">
                          <div className="p-3 bg-blue-100 rounded-xl">
                            <IconCalendarTime className="w-5 h-5 text-blue-600" />
                          </div>
                          <div>
                            <h3 className="font-semibold text-slate-900">Time Slots / Jadwal Booking</h3>
                            <p className="text-sm text-slate-600 mt-1">
                              Aktifkan jika jasa ini membutuhkan pemilihan hari dan jam spesifik.
                            </p>
                            <p className="text-xs text-slate-500 mt-1">
                              Nonaktifkan untuk: joki ML, desain grafis, jasa online tanpa jadwal tetap.
                            </p>
                          </div>
                        </div>
                        <div className="flex items-center gap-3 shrink-0">
                          <span className={`text-xs font-semibold uppercase tracking-wide ${
                            timeSlotsEnabled ? 'text-blue-600' : 'text-slate-400'
                          }`}>
                            {timeSlotsEnabled ? 'Aktif' : 'Nonaktif'}
                          </span>
                          <button
                            type="button"
                            onClick={() => setTimeSlotsEnabled(!timeSlotsEnabled)}
                            className={`relative inline-flex h-6 w-11 items-center rounded-full transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 ${
                              timeSlotsEnabled ? 'bg-blue-600' : 'bg-slate-300'
                            }`}
                            role="switch"
                            aria-checked={timeSlotsEnabled}
                          >
                            <span
                              className={`inline-block h-4 w-4 transform rounded-full bg-white shadow transition-transform ${
                                timeSlotsEnabled ? 'translate-x-6' : 'translate-x-1'
                              }`}
                            />
                          </button>
                        </div>
                      </div>
                    </motion.div>

                    {templates && Object.keys(templates).length > 0 && (
                      <motion.div 
                        variants={staggerItem}
                        initial="hidden"
                        animate="show"
                        className="p-4 bg-slate-50 rounded-xl border border-slate-200"
                      >
                        <div className="flex items-center justify-between">
                          <div className="flex items-center gap-3">
                            <IconTemplate className="w-5 h-5 text-slate-400" />
                            <div>
                              <p className="text-sm font-medium text-slate-700">Gunakan Template</p>
                              <p className="text-xs text-slate-500">Pilih template sesuai jenis jasa</p>
                            </div>
                          </div>
                          <button
                            type="button"
                            onClick={() => setShowTemplateModal(true)}
                            className="px-4 py-2 text-sm font-medium text-blue-600 hover:text-blue-700 hover:bg-blue-50 rounded-lg transition-colors"
                          >
                            Pilih Template
                          </button>
                        </div>
                      </motion.div>
                    )}

                    <motion.div variants={staggerItem} initial="hidden" animate="show">
                      <div className="flex items-center justify-between mb-4">
                        <h3 className="font-semibold text-slate-900">
                          Field Saat Ini
                          <span className="ml-2 text-sm font-normal text-slate-500">
                            {fields.length}/15
                          </span>
                        </h3>
                        {fields.length > 0 && (
                          <button
                            type="button"
                            onClick={() => setFields([])}
                            className="text-xs text-red-600 hover:text-red-700 hover:underline"
                          >
                            Hapus Semua
                          </button>
                        )}
                      </div>

                      {fields.length === 0 ? (
                        <div className="py-12 text-center border-2 border-dashed border-slate-200 rounded-xl">
                          <p className="text-sm text-slate-500">Belum ada field. Tambahkan field di bawah.</p>
                        </div>
                      ) : (
                        <div className="space-y-2">
                          <AnimatePresence>
                            {fields.map((field, index) => (
                              <motion.div
                                key={field.name + index}
                                layout
                                initial={{ opacity: 0, y: 20 }}
                                animate={{ opacity: 1, y: 0 }}
                                exit={{ opacity: 0, x: -20 }}
                                transition={transition}
                                className="group flex items-center gap-4 p-4 bg-slate-50 hover:bg-slate-100 rounded-xl border border-slate-200 transition-colors"
                              >
                                <span className="flex items-center justify-center w-8 h-8 bg-slate-200 text-slate-600 text-xs font-bold rounded-lg">
                                  {String(index + 1).padStart(2, '0')}
                                </span>
                                <div className="flex-1 min-w-0">
                                  <p className="font-medium text-slate-900">{field.label}</p>
                                  <p className="text-xs text-slate-500 mt-0.5">
                                    <span className="font-mono">{field.name}</span>
                                    <span className="mx-1.5">•</span>
                                    <span>{field.type}</span>
                                    <span className="mx-1.5">•</span>
                                    <span className={field.required ? 'text-blue-600 font-medium' : ''}>
                                      {field.required ? 'Wajib' : 'Opsional'}
                                    </span>
                                  </p>
                                </div>
                                <div className="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                  <button
                                    type="button"
                                    onClick={() => moveField(index, 'up')}
                                    disabled={index === 0}
                                    className="p-1.5 text-slate-400 hover:text-slate-600 hover:bg-white rounded-lg transition-colors disabled:opacity-30 disabled:cursor-not-allowed"
                                  >
                                    <IconChevronUp className="w-4 h-4" />
                                  </button>
                                  <button
                                    type="button"
                                    onClick={() => moveField(index, 'down')}
                                    disabled={index === fields.length - 1}
                                    className="p-1.5 text-slate-400 hover:text-slate-600 hover:bg-white rounded-lg transition-colors disabled:opacity-30 disabled:cursor-not-allowed"
                                  >
                                    <IconChevronDown className="w-4 h-4" />
                                  </button>
                                  <button
                                    type="button"
                                    onClick={() => removeField(index)}
                                    className="p-1.5 text-red-500 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                                  >
                                    <IconTrash className="w-4 h-4" />
                                  </button>
                                </div>
                              </motion.div>
                            ))}
                          </AnimatePresence>
                        </div>
                      )}
                    </motion.div>

                    <motion.div 
                      variants={staggerItem}
                      initial="hidden"
                      animate="show"
                      className="p-5 bg-slate-50 rounded-xl border border-slate-200"
                    >
                      <h4 className="font-semibold text-slate-900 mb-4">Tambah Field Baru</h4>
                      <div className="grid sm:grid-cols-2 gap-4">
                        <div>
                          <label className="block text-xs font-semibold text-slate-600 uppercase tracking-wide mb-2">
                            Label
                          </label>
                          <input
                            type="text"
                            value={newField.label}
                            onChange={(e) => setNewField({ ...newField, label: e.target.value })}
                            placeholder="Rank Sekarang"
                            className="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                          />
                        </div>
                        <div>
                          <label className="block text-xs font-semibold text-slate-600 uppercase tracking-wide mb-2">
                            Tipe Field
                          </label>
                          <select
                            value={newField.type}
                            onChange={(e) => setNewField({ ...newField, type: e.target.value })}
                            className="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent cursor-pointer"
                          >
                            <option value="text">Short Text</option>
                            <option value="textarea">Long Text</option>
                            <option value="number">Number</option>
                            <option value="select">Dropdown</option>
                            <option value="radio">Radio Button</option>
                            <option value="checkbox">Checkbox</option>
                            <option value="date">Date</option>
                            <option value="email">Email</option>
                            <option value="url">URL</option>
                          </select>
                        </div>
                        <div>
                          <label className="block text-xs font-semibold text-slate-600 uppercase tracking-wide mb-2">
                            Status
                          </label>
                          <select
                            value={newField.required ? '1' : '0'}
                            onChange={(e) => setNewField({ ...newField, required: e.target.value === '1' })}
                            className="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent cursor-pointer"
                          >
                            <option value="0">Opsional</option>
                            <option value="1">Wajib</option>
                          </select>
                        </div>
                        <div>
                          <label className="block text-xs font-semibold text-slate-600 uppercase tracking-wide mb-2">
                            Placeholder
                          </label>
                          <input
                            type="text"
                            value={newField.placeholder}
                            onChange={(e) => setNewField({ ...newField, placeholder: e.target.value })}
                            placeholder="Teks bantuan"
                            className="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                          />
                        </div>
                        {['select', 'radio', 'checkbox'].includes(newField.type) && (
                          <div className="sm:col-span-2">
                            <label className="block text-xs font-semibold text-slate-600 uppercase tracking-wide mb-2">
                              Opsi (pisahkan dengan koma)
                            </label>
                            <input
                              type="text"
                              value={newField.options}
                              onChange={(e) => setNewField({ ...newField, options: e.target.value })}
                              placeholder="Opsi 1, Opsi 2, Opsi 3"
                              className="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            />
                          </div>
                        )}
                      </div>
                      <div className="mt-4 flex justify-end">
                        <button
                          type="button"
                          onClick={addField}
                          disabled={!newField.label.trim() || fields.length >= 15}
                          className="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                        >
                          <IconPlus className="w-4 h-4" />
                          Tambah Field
                        </button>
                      </div>
                    </motion.div>
                  </div>

                  <div className="px-6 lg:px-8 py-4 bg-slate-50 border-t border-slate-200 flex items-center justify-end gap-3">
                    <a
                      href={myServicesRoute}
                      className="px-5 py-2.5 text-sm font-medium text-slate-600 hover:text-slate-900 transition-colors"
                    >
                      Batal
                    </a>
                    <button
                      type="button"
                      onClick={() => {
                        // Livewire will handle this
                        if (window.Livewire) {
                          window.Livewire.dispatch('saveConfig');
                        }
                      }}
                      className="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all shadow-lg shadow-blue-600/25"
                    >
                      <IconDeviceFloppy className="w-4 h-4" />
                      Simpan Konfigurasi
                    </button>
                  </div>
                </motion.div>
              )}
            </AnimatePresence>
          </motion.main>
        </div>
      </div>

      <AnimatePresence>
        {showTemplateModal && (
          <motion.div
            initial={{ opacity: 0 }}
            animate={{ opacity: 1 }}
            exit={{ opacity: 0 }}
            className="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4"
            onClick={() => setShowTemplateModal(false)}
          >
            <motion.div
              initial={{ scale: 0.95, opacity: 0 }}
              animate={{ scale: 1, opacity: 1 }}
              exit={{ scale: 0.95, opacity: 0 }}
              transition={transition}
              className="w-full max-w-2xl bg-white rounded-2xl shadow-2xl overflow-hidden"
              onClick={(e) => e.stopPropagation()}
            >
              <div className="px-6 py-5 border-b border-slate-200 flex items-center justify-between">
                <h3 className="text-lg font-semibold text-slate-900">Pilih Template</h3>
                <button
                  type="button"
                  onClick={() => setShowTemplateModal(false)}
                  className="p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-lg transition-colors"
                >
                  <IconX className="w-5 h-5" />
                </button>
              </div>
              <div className="p-6 max-h-96 overflow-y-auto">
                <div className="grid sm:grid-cols-2 gap-3">
                  {Object.entries(templates || {}).map(([key, template]) => (
                    <button
                      key={key}
                      type="button"
                      onClick={() => applyTemplate(key)}
                      className="p-4 text-left border border-slate-200 rounded-xl hover:border-blue-400 hover:bg-blue-50 transition-all"
                    >
                      <p className="font-medium text-slate-900">{template.name}</p>
                      <p className="text-xs text-slate-500 mt-1">{template.fields?.length || 0} field</p>
                    </button>
                  ))}
                </div>
              </div>
            </motion.div>
          </motion.div>
        )}
      </AnimatePresence>
    </div>
  );
};

export default ServiceEditor;
