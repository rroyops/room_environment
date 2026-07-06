import React, { useState } from 'react';
import { Member, GalleryItem, Activity, Announcement, ContactMessage } from '../types';

interface PublicProps {
  currentView: 'home' | 'about' | 'gallery' | 'members' | 'activities' | 'contact';
  onNavigate: (view: 'home' | 'about' | 'gallery' | 'members' | 'activities' | 'contact') => void;
  members: Member[];
  gallery: GalleryItem[];
  activities: Activity[];
  announcements: Announcement[];
  onUploadPhoto: (p: Omit<GalleryItem, 'id' | 'createdAt' | 'isApproved'>) => void;
  onAddMessage: (msg: Omit<ContactMessage, 'id' | 'createdAt' | 'isRead'>) => void;
}

export const PublicViews: React.FC<PublicProps> = ({
  currentView, onNavigate, members, gallery, activities, announcements, onUploadPhoto, onAddMessage
}) => {
  // Global Search
  const [globalSearch, setGlobalSearch] = useState('');
  const [searchResults, setSearchResults] = useState<{ type: string; title: string; desc: string }[] | null>(null);

  // Gallery view states
  const [galCategory, setGalCategory] = useState('All');
  const [gTitle, setGTitle] = useState('');
  const [gDesc, setGDesc] = useState('');
  const [gUrl, setGUrl] = useState('');
  const [gUser, setGUser] = useState('');
  const [gCat, setGCat] = useState('Research');
  const [galSuccess, setGalSuccess] = useState('');

  // Members view states
  const [memSearch, setMemSearch] = useState('');
  const [memRoleFilter, setMemRoleFilter] = useState('All');
  const [selectedMember, setSelectedMember] = useState<Member | null>(null);
  const [memPage, setMemPage] = useState(1);
  const memPerPage = 3;

  // Contact state
  const [cName, setCName] = useState('');
  const [cEmail, setCEmail] = useState('');
  const [cSubject, setCSubject] = useState('');
  const [cMessage, setCMessage] = useState('');
  const [contactSuccess, setContactSuccess] = useState('');

  // Handle Global Search
  const handleGlobalSearch = (e: React.FormEvent) => {
    e.preventDefault();
    if (!globalSearch.trim()) {
      setSearchResults(null);
      return;
    }
    const query = globalSearch.toLowerCase();
    const results: { type: string; title: string; desc: string }[] = [];

    members.forEach(m => {
      if (m.name.toLowerCase().includes(query) || m.role.toLowerCase().includes(query) || m.bio.toLowerCase().includes(query)) {
        results.push({ type: 'Member', title: m.name, desc: m.role });
      }
    });

    activities.forEach(a => {
      if (a.title.toLowerCase().includes(query) || a.description.toLowerCase().includes(query)) {
        results.push({ type: 'Activity', title: a.title, desc: a.description });
      }
    });

    announcements.forEach(an => {
      if (an.title.toLowerCase().includes(query) || an.content.toLowerCase().includes(query)) {
        results.push({ type: 'Bulletin', title: an.title, desc: an.content });
      }
    });

    setSearchResults(results);
  };

  // Upload handler
  const handlePhotoUpload = (e: React.FormEvent) => {
    e.preventDefault();
    setGalSuccess('');

    if (!gTitle || !gDesc || !gUrl || !gUser) {
      alert('All upload parameters are required.');
      return;
    }

    onUploadPhoto({
      title: gTitle,
      description: gDesc,
      imagePath: gUrl,
      uploadedBy: gUser,
      category: gCat
    });

    setGalSuccess('Photo submitted successfully! Pending admin moderation approval.');
    setGTitle(''); setGDesc(''); setGUrl(''); setGUser('');
  };

  // Contact handler
  const handleContactSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    setContactSuccess('');

    if (!cName || !cEmail || !cSubject || !cMessage) {
      alert('Please fill out all contact fields.');
      return;
    }

    onAddMessage({
      name: cName,
      email: cEmail,
      subject: cSubject,
      message: cMessage
    });

    setContactSuccess('Your message has been received! The Room 320 support team will reply shortly.');
    setCName(''); setCEmail(''); setCSubject(''); setCMessage('');
  };

  return (
    <div className="space-y-12">
      {/* 🧭 GEOMETRIC BALANCE HERO GRID */}
      {currentView === 'home' && (
        <div className="space-y-8">
          <div className="grid grid-cols-1 lg:grid-cols-12 border-2 border-slate-900 dark:border-zinc-100 bg-white dark:bg-zinc-900">
            {/* Left Column: Hero Text & Search */}
            <section className="col-span-12 lg:col-span-7 lg:border-r-2 border-slate-900 dark:border-zinc-100 p-6 md:p-12 flex flex-col justify-center bg-white dark:bg-zinc-900">
              <div className="mb-6 inline-block self-start px-3.5 py-1 bg-sky-100 dark:bg-sky-950/50 text-sky-700 dark:text-sky-300 text-xs font-black uppercase tracking-wider border border-sky-300 dark:border-sky-800">
                Community & Innovation
              </div>
              <h1 className="text-4xl md:text-6xl font-black leading-none mb-6 tracking-tighter uppercase text-slate-900 dark:text-white">
                BUILDING <br/> THE FUTURE <br/> <span className="text-sky-600 dark:text-sky-400 underline decoration-4 underline-offset-4">TOGETHER.</span>
              </h1>
              <p className="text-sm md:text-base text-slate-600 dark:text-zinc-300 max-w-md mb-8 leading-relaxed">
                A professional collective environment designed for high-impact activities, seamless member collaboration, and architectural precision inside Room No. 320.
              </p>
              
              {/* Global search form */}
              <form onSubmit={handleGlobalSearch} className="mb-8 max-w-md flex flex-col sm:flex-row gap-2 border-2 border-slate-900 dark:border-zinc-100 p-1.5 bg-neutral-50 dark:bg-zinc-950">
                <input
                  type="text"
                  placeholder="Search members, activities, bulletins..."
                  className="flex-1 px-3 py-2 text-xs bg-transparent text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none font-bold uppercase tracking-wider"
                  value={globalSearch}
                  onChange={e => {
                    setGlobalSearch(e.target.value);
                    if (!e.target.value) setSearchResults(null);
                  }}
                />
                <button type="submit" className="px-5 py-2.5 bg-slate-900 hover:bg-sky-600 text-white dark:bg-zinc-100 dark:text-slate-900 dark:hover:bg-sky-500 font-black text-xs uppercase tracking-wider transition-colors cursor-pointer">
                  Search
                </button>
              </form>

              {searchResults !== null && (
                <div className="border-2 border-slate-900 dark:border-zinc-100 bg-white dark:bg-zinc-900 text-left p-4 rounded-none shadow-xs max-h-60 overflow-y-auto space-y-3 mb-6">
                  <div className="flex justify-between items-center pb-2 border-b-2 border-slate-900 dark:border-zinc-100">
                    <span className="text-xs font-black uppercase tracking-wider text-slate-900 dark:text-white">Search Results ({searchResults.length})</span>
                    <button type="button" onClick={() => { setSearchResults(null); setGlobalSearch(''); }} className="text-xs text-red-600 hover:underline uppercase font-bold tracking-wider">Clear</button>
                  </div>
                  {searchResults.length === 0 ? (
                    <p className="text-xs text-slate-400 text-center py-4 font-mono">No matching records found.</p>
                  ) : (
                    searchResults.map((res, i) => (
                      <div key={i} className="text-xs pb-2 border-b last:border-0 last:pb-0 border-slate-100 dark:border-zinc-800">
                        <span className="px-2 py-0.5 text-[9px] font-mono bg-sky-100 text-sky-800 dark:bg-sky-950 dark:text-sky-300 border border-sky-200 dark:border-sky-900 rounded-none mr-2 uppercase font-bold">{res.type}</span>
                        <strong className="text-slate-900 dark:text-white">{res.title}</strong>
                        <p className="text-slate-500 dark:text-zinc-400 text-[11px] mt-0.5 line-clamp-1">{res.desc}</p>
                      </div>
                    ))
                  )}
                </div>
              )}

              <div className="flex flex-wrap gap-3">
                <button 
                  onClick={() => onNavigate('members')}
                  className="px-6 py-3.5 bg-slate-900 hover:bg-sky-600 dark:bg-zinc-100 dark:text-slate-900 dark:hover:bg-sky-500 text-white font-black uppercase tracking-widest text-xs transition-transform hover:translate-x-1 cursor-pointer border-2 border-slate-900 dark:border-zinc-100"
                >
                  Join Member
                </button>
                <button 
                  onClick={() => onNavigate('gallery')}
                  className="px-6 py-3.5 border-2 border-slate-900 dark:border-zinc-300 text-slate-900 dark:text-zinc-100 font-black uppercase tracking-widest text-xs hover:bg-slate-100 dark:hover:bg-zinc-800 transition-colors"
                >
                  Explore Gallery
                </button>
              </div>
            </section>

            {/* Right Column: Data Grid & Announcements */}
            <section className="col-span-12 lg:col-span-5 flex flex-col bg-slate-50 dark:bg-zinc-950">
              {/* Recent Announcements Panel */}
              <div className="flex-1 p-6 md:p-10 border-b-2 border-slate-900 dark:border-zinc-100">
                <h3 className="text-xs font-black uppercase tracking-widest mb-6 flex items-center gap-2 text-slate-900 dark:text-white">
                  <span className="w-2.5 h-2.5 bg-sky-600"></span> Recent Announcements
                </h3>
                <div className="space-y-6">
                  {announcements.slice(0, 3).map(an => (
                    <div key={an.id} className="group cursor-pointer border-l-2 border-slate-300 dark:border-zinc-700 pl-4 space-y-1 hover:border-sky-600 dark:hover:border-sky-400 transition-colors">
                      <p className="text-[10px] font-bold text-sky-600 dark:text-sky-400 font-mono uppercase">
                        {new Date(an.createdAt).toLocaleDateString(undefined, { month: 'short', day: 'numeric', year: 'numeric' })}
                      </p>
                      <h4 className="text-sm font-bold group-hover:underline text-slate-900 dark:text-white">
                        {an.title}
                      </h4>
                      <p className="text-xs text-slate-500 dark:text-zinc-400 line-clamp-2 leading-relaxed">
                        {an.content}
                      </p>
                    </div>
                  ))}
                </div>
              </div>

              {/* Statistics/Visuals Grid */}
              <div className="h-fit grid grid-cols-2 bg-slate-900 text-white">
                <div className="border-r-2 border-slate-800 p-6 flex flex-col justify-end">
                  <span className="text-sky-400 font-mono text-3xl md:text-4xl font-black tracking-tighter">{members.length}</span>
                  <span className="uppercase text-[9px] tracking-widest font-bold text-zinc-400 block mt-1">Active Members</span>
                </div>
                <div className="p-6 flex flex-col justify-end">
                  <span className="text-sky-400 font-mono text-3xl md:text-4xl font-black tracking-tighter">320</span>
                  <span className="uppercase text-[9px] tracking-widest font-bold text-zinc-400 block mt-1">Environment ID</span>
                </div>
                <div className="col-span-2 bg-sky-600 dark:bg-sky-700 h-14 flex items-center justify-between px-6 border-t-2 border-slate-800">
                  <span className="text-white font-black uppercase tracking-widest text-[10px]">Admin Dashboard Status</span>
                  <span className="bg-white text-sky-600 px-2.5 py-1 text-[9px] font-black tracking-wider">ONLINE</span>
                </div>
              </div>
            </section>
          </div>

          {/* Additional Initiatives Carousel or Feed on Home */}
          <div className="border-2 border-slate-900 dark:border-zinc-100 bg-white dark:bg-zinc-900 p-6 md:p-8">
            <div className="flex justify-between items-center border-b-2 border-slate-900 dark:border-zinc-100 pb-3 mb-6">
              <h3 className="font-black uppercase text-base tracking-tight text-slate-900 dark:text-white flex items-center gap-2">
                <span className="w-2.5 h-2.5 bg-slate-900 dark:bg-zinc-100"></span> Recent Initiatives & Projects
              </h3>
              <button 
                onClick={() => onNavigate('activities')} 
                className="text-xs font-black uppercase tracking-wider text-sky-600 dark:text-sky-400 hover:underline cursor-pointer"
              >
                View All Sprints
              </button>
            </div>

            <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
              {activities.slice(0, 2).map(act => (
                <div key={act.id} className="flex flex-col sm:flex-row gap-4 items-start p-4 border-2 border-slate-900 dark:border-zinc-700 hover:bg-slate-50 dark:hover:bg-zinc-850 transition-colors">
                  <div className="w-full sm:w-28 h-20 bg-slate-100 border border-slate-300 dark:border-zinc-700 overflow-hidden shrink-0">
                    <img src={act.imagePath} alt="" className="w-full h-full object-cover" />
                  </div>
                  <div className="space-y-1.5 flex-1">
                    <div className="flex items-center justify-between">
                      <span className="text-[9px] font-bold font-mono text-sky-600 dark:text-sky-400 bg-sky-100 dark:bg-sky-950/60 px-2 py-0.5 border border-sky-200 dark:border-sky-900 uppercase">
                        {act.activityDate}
                      </span>
                    </div>
                    <h4 className="font-bold text-sm text-slate-900 dark:text-white uppercase tracking-tight">{act.title}</h4>
                    <p className="text-slate-500 dark:text-zinc-400 text-xs leading-relaxed line-clamp-2">{act.description}</p>
                  </div>
                </div>
              ))}
            </div>
          </div>
        </div>
      )}

      {/* ABOUT VIEW */}
      {currentView === 'about' && (
        <div className="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
          <div className="lg:col-span-8 bg-white dark:bg-zinc-900 border-2 border-slate-900 dark:border-zinc-100 p-6 md:p-8 space-y-6">
            <h2 className="text-3xl font-black uppercase tracking-tighter text-slate-900 dark:text-white">Our Environmental Roots</h2>
            <p className="text-sm text-slate-600 dark:text-zinc-300 leading-relaxed">
              Established inside room 320, our station is dedicated to testing active bio-remediation grids, combining automated plant irrigation cycles with cloud-connected temperature, dust particulate, and carbon dioxide metrics.
            </p>

            <h3 className="font-black uppercase tracking-tight text-sm text-slate-900 dark:text-white pt-4 border-t-2 border-slate-900 dark:border-zinc-700">Automated Smart Wall Layout</h3>
            <div className="grid grid-cols-1 sm:grid-cols-3 gap-4 bg-slate-50 dark:bg-zinc-950 p-4 border-2 border-slate-900 dark:border-zinc-700">
              <div className="p-4 bg-white dark:bg-zinc-900 border-2 border-slate-900 dark:border-zinc-700 text-center">
                <span className="font-mono text-xs text-sky-600 dark:text-sky-400 font-black uppercase tracking-wider block">Zone Alpha</span>
                <p className="text-[11px] text-slate-500 dark:text-zinc-400 mt-2 font-bold uppercase">Automated fern hydroponic tubes.</p>
              </div>
              <div className="p-4 bg-white dark:bg-zinc-900 border-2 border-slate-900 dark:border-zinc-700 text-center">
                <span className="font-mono text-xs text-sky-600 dark:text-sky-400 font-black uppercase tracking-wider block">Zone Beta</span>
                <p className="text-[11px] text-slate-500 dark:text-zinc-400 mt-2 font-bold uppercase">Gas monitoring and fan relays.</p>
              </div>
              <div className="p-4 bg-white dark:bg-zinc-900 border-2 border-slate-900 dark:border-zinc-700 text-center">
                <span className="font-mono text-xs text-sky-600 dark:text-sky-400 font-black uppercase tracking-wider block">Zone Gamma</span>
                <p className="text-[11px] text-slate-500 dark:text-zinc-400 mt-2 font-bold uppercase">O2 enrichment output panels.</p>
              </div>
            </div>
          </div>

          <div className="lg:col-span-4 bg-white dark:bg-zinc-900 border-2 border-slate-900 dark:border-zinc-100 p-6 space-y-4">
            <h3 className="font-black uppercase tracking-tight text-sm text-slate-900 dark:text-white">Station Highlights</h3>
            <ul className="space-y-3 text-xs text-slate-600 dark:text-zinc-300 font-bold uppercase tracking-wider">
              <li className="flex items-center gap-2"><span className="w-2 h-2 bg-sky-600 shrink-0"></span> Low-cost telemetry</li>
              <li className="flex items-center gap-2"><span className="w-2 h-2 bg-sky-600 shrink-0"></span> Rainwater collector grids</li>
              <li className="flex items-center gap-2"><span className="w-2 h-2 bg-sky-600 shrink-0"></span> Air toxicity warnings</li>
              <li className="flex items-center gap-2"><span className="w-2 h-2 bg-sky-600 shrink-0"></span> Open Source backend ledger</li>
            </ul>
          </div>
        </div>
      )}

      {/* GALLERY VIEW */}
      {currentView === 'gallery' && (
        <div className="space-y-8">
          <div className="flex flex-wrap justify-between items-center gap-4 border-b-2 border-slate-900 dark:border-zinc-100 pb-4">
            <div>
              <h2 className="text-3xl font-black uppercase tracking-tighter text-slate-900 dark:text-white">Environmental Gallery</h2>
              <p className="text-xs text-slate-500 mt-0.5">Explore pictures from active test zones or upload your own photo.</p>
            </div>

            <div className="flex flex-wrap gap-1 bg-slate-100 dark:bg-zinc-950 p-1 border-2 border-slate-900 dark:border-zinc-700">
              {['All', 'Research', 'Initiatives', 'Events'].map(cat => (
                <button
                  key={cat}
                  onClick={() => setGalCategory(cat)}
                  className={`px-4 py-2 text-xs font-black uppercase tracking-wider transition-colors ${
                    galCategory === cat
                      ? 'bg-slate-900 text-white dark:bg-zinc-100 dark:text-slate-900'
                      : 'text-slate-600 dark:text-zinc-400 hover:bg-slate-200 dark:hover:bg-zinc-800'
                  }`}
                >
                  {cat}
                </button>
              ))}
            </div>
          </div>

          <div className="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            {/* Gallery list */}
            <div className="lg:col-span-8 grid grid-cols-1 sm:grid-cols-2 gap-6">
              {gallery
                .filter(item => (galCategory === 'All' || item.category === galCategory) && item.isApproved)
                .map(item => (
                  <div key={item.id} className="bg-white dark:bg-zinc-900 border-2 border-slate-900 dark:border-zinc-700 flex flex-col justify-between">
                    <div className="h-48 bg-slate-100 dark:bg-zinc-850 border-b-2 border-slate-900 dark:border-zinc-700">
                      <img src={item.imagePath} alt="" className="w-full h-full object-cover" />
                    </div>
                    <div className="p-4 space-y-2">
                      <span className="px-2 py-0.5 bg-sky-100 dark:bg-sky-950 text-sky-800 dark:text-sky-300 text-[9px] font-mono border border-sky-300 dark:border-sky-900 uppercase font-black tracking-wider">
                        {item.category}
                      </span>
                      <h4 className="font-black text-sm text-slate-900 dark:text-white uppercase tracking-tight truncate">{item.title}</h4>
                      <p className="text-xs text-slate-500 dark:text-zinc-400 line-clamp-2 leading-relaxed">{item.description}</p>
                      <div className="pt-2 border-t border-slate-100 dark:border-zinc-800 flex justify-between items-center text-[10px] text-slate-400 font-mono">
                        <span>By: <strong className="text-slate-600 dark:text-zinc-300 uppercase">{item.uploadedBy}</strong></span>
                      </div>
                    </div>
                  </div>
                ))}
            </div>

            {/* Upload form */}
            <div className="lg:col-span-4 bg-white dark:bg-zinc-900 border-2 border-slate-900 dark:border-zinc-100 p-5">
              <h3 className="font-black uppercase tracking-tight text-sm text-slate-900 dark:text-white mb-4 border-b-2 border-slate-900 dark:border-zinc-700 pb-2 flex items-center gap-2">
                <span className="w-2.5 h-2.5 bg-sky-600"></span> Share Photograph
              </h3>
              {galSuccess && (
                <div className="p-3 bg-sky-100 dark:bg-sky-950/40 border-2 border-sky-600 text-sky-700 dark:text-sky-300 text-xs font-bold mb-3 uppercase tracking-wider text-center">
                  {galSuccess}
                </div>
              )}
              <form onSubmit={handlePhotoUpload} className="space-y-4 text-xs">
                <div>
                  <label className="block font-black uppercase tracking-wider mb-1 text-slate-700 dark:text-zinc-300">Photo Title</label>
                  <input type="text" className="w-full p-2.5 bg-slate-50 dark:bg-zinc-850 border-2 border-slate-900 dark:border-zinc-700 font-bold focus:outline-none" value={gTitle} onChange={e => setGTitle(e.target.value)} required />
                </div>
                <div>
                  <label className="block font-black uppercase tracking-wider mb-1 text-slate-700 dark:text-zinc-300">Uploader Name</label>
                  <input type="text" className="w-full p-2.5 bg-slate-50 dark:bg-zinc-850 border-2 border-slate-900 dark:border-zinc-700 font-bold focus:outline-none" value={gUser} onChange={e => setGUser(e.target.value)} required />
                </div>
                <div>
                  <label className="block font-black uppercase tracking-wider mb-1 text-slate-700 dark:text-zinc-300">Category</label>
                  <select className="w-full p-2.5 bg-slate-50 dark:bg-zinc-850 border-2 border-slate-900 dark:border-zinc-700 font-bold focus:outline-none" value={gCat} onChange={e => setGCat(e.target.value)}>
                    <option>Research</option>
                    <option>Initiatives</option>
                    <option>Events</option>
                  </select>
                </div>
                <div>
                  <label className="block font-black uppercase tracking-wider mb-1 text-slate-700 dark:text-zinc-300">Unsplash/Image URL</label>
                  <input type="url" className="w-full p-2.5 bg-slate-50 dark:bg-zinc-850 border-2 border-slate-900 dark:border-zinc-700 font-mono focus:outline-none" value={gUrl} onChange={e => setGUrl(e.target.value)} placeholder="https://images.unsplash.com/..." required />
                </div>
                <div>
                  <label className="block font-black uppercase tracking-wider mb-1 text-slate-700 dark:text-zinc-300">Brief Description</label>
                  <textarea className="w-full p-2.5 bg-slate-50 dark:bg-zinc-850 border-2 border-slate-900 dark:border-zinc-700 font-bold focus:outline-none" rows={3} value={gDesc} onChange={e => setGDesc(e.target.value)} required />
                </div>

                <button type="submit" className="w-full py-3 bg-slate-900 hover:bg-sky-600 dark:bg-zinc-100 dark:text-slate-900 dark:hover:bg-sky-500 text-white font-black uppercase tracking-widest text-xs transition-colors cursor-pointer border-2 border-slate-900 dark:border-zinc-100">
                  Submit for Approval
                </button>
              </form>
            </div>
          </div>
        </div>
      )}

      {/* MEMBERS DIRECTORY */}
      {currentView === 'members' && (
        <div className="space-y-8">
          <div className="flex flex-wrap justify-between items-center gap-4 border-b-2 border-slate-900 dark:border-zinc-100 pb-4">
            <div>
              <h2 className="text-3xl font-black uppercase tracking-tighter text-slate-900 dark:text-white">Active Researchers</h2>
              <p className="text-xs text-slate-500 mt-0.5">Contact coordinates and biographies for our active lab participants.</p>
            </div>

            <div className="flex flex-wrap gap-2 text-xs">
              <input
                type="text"
                placeholder="Search researchers..."
                className="p-2.5 border-2 border-slate-900 dark:border-zinc-700 bg-white dark:bg-zinc-900 focus:outline-none font-bold uppercase tracking-wider text-xs"
                value={memSearch}
                onChange={e => { setMemSearch(e.target.value); setMemPage(1); }}
              />
              <select
                className="p-2.5 border-2 border-slate-900 dark:border-zinc-700 bg-white dark:bg-zinc-900 focus:outline-none font-bold uppercase tracking-wider text-xs"
                value={memRoleFilter}
                onChange={e => { setMemRoleFilter(e.target.value); setMemPage(1); }}
              >
                <option value="All">All Positions</option>
                <option value="Chief">Chief Advisor</option>
                <option value="Researcher">Lead Eco-Researcher</option>
                <option value="Architect">IoT Systems Architect</option>
                <option value="Lead">Community Outreach Lead</option>
              </select>
            </div>
          </div>

          {/* Members list */}
          <div className="grid grid-cols-1 sm:grid-cols-3 gap-6">
            {members
              .filter(m => {
                const matchesSearch = m.name.toLowerCase().includes(memSearch.toLowerCase()) || m.bio.toLowerCase().includes(memSearch.toLowerCase());
                const matchesRole = memRoleFilter === 'All' || m.role.toLowerCase().includes(memRoleFilter.toLowerCase());
                return matchesSearch && matchesRole;
              })
              .slice((memPage - 1) * memPerPage, memPage * memPerPage)
              .map(m => (
                <div key={m.id} className="bg-white dark:bg-zinc-900 border-2 border-slate-900 dark:border-zinc-700 p-6 flex flex-col justify-between">
                  <div>
                    <div className="w-20 h-20 mx-auto overflow-hidden border-2 border-slate-900 dark:border-zinc-500">
                      <img src={m.photo} alt={m.name} className="w-full h-full object-cover" />
                    </div>
                    <h3 className="font-black uppercase tracking-tight text-slate-900 dark:text-white mt-4">{m.name}</h3>
                    <span className="text-[10px] text-sky-700 bg-sky-50 dark:bg-sky-950/50 dark:text-sky-300 font-mono font-black uppercase tracking-wider px-2 py-0.5 border border-sky-300 dark:border-sky-900 inline-block mt-1">
                      {m.role}
                    </span>
                    <p className="text-xs text-slate-500 dark:text-zinc-400 mt-4 line-clamp-3 leading-relaxed">
                      "{m.bio}"
                    </p>
                  </div>

                  <div className="border-t-2 border-slate-900 dark:border-zinc-800 mt-4 pt-4 text-xs text-left space-y-1 text-slate-500 dark:text-zinc-400">
                    <div>Email: <strong className="text-slate-900 dark:text-white font-mono">{m.email}</strong></div>
                    {m.phone && <div>Phone: <strong className="text-slate-900 dark:text-white font-mono">{m.phone}</strong></div>}
                    <button
                      onClick={() => setSelectedMember(m)}
                      className="w-full py-2 bg-slate-100 hover:bg-sky-600 hover:text-white dark:bg-zinc-800 dark:hover:bg-sky-500 text-slate-900 dark:text-zinc-100 font-black uppercase tracking-wider mt-3 transition-colors text-[10px] border-2 border-slate-900 dark:border-zinc-700 cursor-pointer"
                    >
                      View Full Bio
                    </button>
                  </div>
                </div>
              ))}
          </div>

          {/* Pagination Indicators */}
          <div className="flex justify-center items-center gap-2 pt-4">
            <button
              onClick={() => setMemPage(p => Math.max(1, p - 1))}
              disabled={memPage === 1}
              className="px-4 py-2 bg-white dark:bg-zinc-900 border-2 border-slate-900 dark:border-zinc-700 text-xs font-black uppercase tracking-wider disabled:opacity-30 cursor-pointer"
            >
              Prev
            </button>
            <span className="text-xs font-black uppercase font-mono px-3 py-1.5 border-2 border-slate-900 dark:border-zinc-700 bg-slate-50 dark:bg-zinc-800">Page {memPage}</span>
            <button
              onClick={() => setMemPage(p => p + 1)}
              disabled={members.filter(m => {
                const matchesSearch = m.name.toLowerCase().includes(memSearch.toLowerCase()) || m.bio.toLowerCase().includes(memSearch.toLowerCase());
                const matchesRole = memRoleFilter === 'All' || m.role.toLowerCase().includes(memRoleFilter.toLowerCase());
                return matchesSearch && matchesRole;
              }).length <= memPage * memPerPage}
              className="px-4 py-2 bg-white dark:bg-zinc-900 border-2 border-slate-900 dark:border-zinc-700 text-xs font-black uppercase tracking-wider disabled:opacity-30 cursor-pointer"
            >
              Next
            </button>
          </div>

          {/* Bio modal */}
          {selectedMember && (
            <div className="fixed inset-0 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 z-50">
              <div className="bg-white dark:bg-zinc-900 border-4 border-slate-900 dark:border-zinc-100 p-6 max-w-md w-full relative space-y-4">
                <button onClick={() => setSelectedMember(null)} className="absolute top-4 right-4 bg-slate-900 text-white w-8 h-8 flex items-center justify-center font-black text-lg hover:bg-sky-600 transition-colors">&times;</button>
                
                <div className="text-center">
                  <div className="w-16 h-16 mx-auto border-2 border-slate-900 dark:border-zinc-500 overflow-hidden mb-3">
                    <img src={selectedMember.photo} alt="" className="w-full h-full object-cover" />
                  </div>
                  <h3 className="text-lg font-black uppercase tracking-tight text-slate-900 dark:text-white">{selectedMember.name}</h3>
                  <span className="text-[10px] text-sky-700 bg-sky-50 dark:bg-sky-950/50 dark:text-sky-300 font-mono font-black uppercase tracking-wider px-2.5 py-0.5 border border-sky-300 dark:border-sky-900 mt-1 inline-block">{selectedMember.role}</span>
                </div>

                <div className="text-xs text-slate-600 dark:text-zinc-300 space-y-2 leading-relaxed">
                  <strong className="font-black uppercase tracking-wider text-[10px] text-slate-900 dark:text-white block border-b border-slate-200 dark:border-zinc-700 pb-1">Full Biography:</strong>
                  <p className="bg-slate-50 dark:bg-zinc-950 p-3 border border-slate-200 dark:border-zinc-800">"{selectedMember.bio}"</p>
                </div>

                <div className="border-t-2 border-slate-900 dark:border-zinc-700 pt-3 text-[11px] text-slate-500 dark:text-zinc-400 space-y-1">
                  <div>Email: <strong className="text-slate-950 dark:text-white font-mono">{selectedMember.email}</strong></div>
                  <div>Phone: <strong className="text-slate-950 dark:text-white font-mono">{selectedMember.phone || 'N/A'}</strong></div>
                  <div>Joined Research: <strong className="text-slate-950 dark:text-white font-mono">{selectedMember.joinedDate}</strong></div>
                </div>
              </div>
            </div>
          )}
        </div>
      )}

      {/* ACTIVITIES TIMELINE */}
      {currentView === 'activities' && (
        <div className="space-y-8 max-w-3xl mx-auto">
          <div className="text-center space-y-2 border-b-2 border-slate-900 dark:border-zinc-100 pb-6">
            <h2 className="text-3xl font-black uppercase tracking-tighter text-slate-900 dark:text-white">Eco Initiatives Registry</h2>
            <p className="text-xs text-slate-500">Historical tracker of public planting sessions, carbon sprint reviews, and API sensor updates.</p>
          </div>

          <div className="space-y-6 relative border-l-4 border-slate-900 dark:border-zinc-200 pl-8 ml-4">
            {activities.map((act) => (
              <div key={act.id} className="relative pb-6 last:pb-0">
                <span className="absolute -left-[40px] top-4 w-4 h-4 bg-sky-600 border-2 border-slate-900 dark:border-zinc-100 shrink-0"></span>
                
                <div className="bg-white dark:bg-zinc-900 border-2 border-slate-900 dark:border-zinc-700 p-5 flex-1 grid grid-cols-1 md:grid-cols-4 gap-4 items-center">
                  <div className="md:col-span-1 h-24 bg-slate-100 dark:bg-zinc-800 border border-slate-300 dark:border-zinc-700 overflow-hidden">
                    <img src={act.imagePath} alt="" className="w-full h-full object-cover" />
                  </div>
                  <div className="md:col-span-3 space-y-2">
                    <span className="text-[9px] font-mono font-black text-sky-700 dark:text-sky-300 bg-sky-50 dark:bg-sky-950/50 border border-sky-300 dark:border-sky-900 px-2 py-0.5 uppercase tracking-wider inline-block">
                      {act.activityDate}
                    </span>
                    <h3 className="font-black uppercase text-sm tracking-tight text-slate-900 dark:text-white">{act.title}</h3>
                    <p className="text-xs text-slate-500 dark:text-zinc-400 leading-relaxed">{act.description}</p>
                  </div>
                </div>
              </div>
            ))}
          </div>
        </div>
      )}

      {/* CONTACT FEEDBACK */}
      {currentView === 'contact' && (
        <div className="max-w-xl mx-auto space-y-6 bg-white dark:bg-zinc-900 p-6 md:p-8 border-2 border-slate-900 dark:border-zinc-100">
          <div className="text-center space-y-2">
            <h2 className="text-3xl font-black uppercase tracking-tighter text-slate-900 dark:text-white">Eco Collaboration Portal</h2>
            <p className="text-xs text-slate-500">Interested in air sensors? Send a proposal directly to room 320 coordinators.</p>
          </div>

          {contactSuccess && (
            <div className="p-3 bg-sky-100 border-2 border-sky-600 text-sky-700 dark:text-sky-300 text-xs font-bold uppercase tracking-wider text-center">
              {contactSuccess}
            </div>
          )}

          <form onSubmit={handleContactSubmit} className="space-y-4 text-xs">
            <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div>
                <label className="block font-black uppercase tracking-wider mb-1 text-slate-700 dark:text-zinc-300">Your Name</label>
                <input type="text" className="w-full p-2.5 bg-slate-50 dark:bg-zinc-850 border-2 border-slate-900 dark:border-zinc-700 font-bold focus:outline-none" value={cName} onChange={e => setCName(e.target.value)} placeholder="e.g. Robert Foster" required />
              </div>
              <div>
                <label className="block font-black uppercase tracking-wider mb-1 text-slate-700 dark:text-zinc-300">Your Email</label>
                <input type="email" className="w-full p-2.5 bg-slate-50 dark:bg-zinc-850 border-2 border-slate-900 dark:border-zinc-700 font-mono focus:outline-none" value={cEmail} onChange={e => setCEmail(e.target.value)} placeholder="e.g. robert@example.com" required />
              </div>
            </div>

            <div>
              <label className="block font-black uppercase tracking-wider mb-1 text-slate-700 dark:text-zinc-300">Inquiry Subject</label>
              <input type="text" className="w-full p-2.5 bg-slate-50 dark:bg-zinc-850 border-2 border-slate-900 dark:border-zinc-700 font-bold focus:outline-none" value={cSubject} onChange={e => setCSubject(e.target.value)} placeholder="e.g. Sensor integration request" required />
            </div>

            <div>
              <label className="block font-black uppercase tracking-wider mb-1 text-slate-700 dark:text-zinc-300">Message Content</label>
              <textarea className="w-full p-2.5 bg-slate-50 dark:bg-zinc-850 border-2 border-slate-900 dark:border-zinc-700 font-bold focus:outline-none" rows={5} value={cMessage} onChange={e => setCMessage(e.target.value)} placeholder="Explain details..." required />
            </div>

            <button type="submit" className="w-full py-3 bg-slate-900 hover:bg-sky-600 dark:bg-zinc-100 dark:text-slate-900 dark:hover:bg-sky-500 text-white font-black uppercase tracking-widest text-xs transition-colors cursor-pointer border-2 border-slate-900 dark:border-zinc-100">
              Deliver Message Securely
            </button>
          </form>
        </div>
      )}
    </div>
  );
};
