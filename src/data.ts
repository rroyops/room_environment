import { User, Member, GalleryItem, Activity, Announcement, ContactMessage } from "./types";

export const defaultUsers: User[] = [
  {
    id: 1,
    username: "admin",
    email: "admin@room320.com",
    role: "admin",
    fullname: "Administrator (Room 320)",
    bio: "Head Coordinator of Room No. 320 Environment and Lead Developer.",
    avatar: "https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?auto=format&fit=crop&q=80&w=200",
    createdAt: "2026-01-01T12:00:00Z"
  },
  {
    id: 2,
    username: "john",
    email: "john@room320.com",
    role: "user",
    fullname: "John Doe",
    bio: "Active resident member and environment research enthusiast.",
    avatar: "https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&q=80&w=200",
    createdAt: "2026-03-10T14:30:00Z"
  }
];

export const defaultMembers: Member[] = [
  {
    id: 1,
    name: "Dr. Sarah Jenkins",
    role: "Chief Advisor / Professor",
    email: "sarah.jenkins@room320.com",
    phone: "+1234567890",
    bio: "Specializes in Environmental Science and Urban Planning with 15+ years of academic research.",
    photo: "https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&q=80&w=200",
    joinedDate: "2024-01-15",
    createdAt: "2024-01-15T09:00:00Z"
  },
  {
    id: 2,
    name: "Alex Rivera",
    role: "Project Manager",
    email: "alex.rivera@room320.com",
    phone: "+1234567891",
    bio: "Lead coordinator of green initiatives, carbon auditing, and sustainable campus integration projects.",
    photo: "https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?auto=format&fit=crop&q=80&w=200",
    joinedDate: "2024-03-10",
    createdAt: "2024-03-10T10:30:00Z"
  },
  {
    id: 3,
    name: "Emily Watson",
    role: "Lead Eco-Researcher",
    email: "emily.watson@room320.com",
    phone: "+1234567892",
    bio: "Focuses on Indoor Air Quality (IAQ) and sensory-stimulating microclimates inside university environments.",
    photo: "https://images.unsplash.com/photo-1580489944761-15a19d654956?auto=format&fit=crop&q=80&w=200",
    joinedDate: "2024-05-01",
    createdAt: "2024-05-01T11:00:00Z"
  },
  {
    id: 4,
    name: "Tariq Al-Mansoor",
    role: "IoT Systems Architect",
    email: "tariq.al@room320.com",
    phone: "+1234567893",
    bio: "Designs sensor arrays for automated tracking of humidity, carbon dioxide, and temperature within Room 320.",
    photo: "https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&q=80&w=200",
    joinedDate: "2024-06-15",
    createdAt: "2024-06-15T14:00:00Z"
  },
  {
    id: 5,
    name: "Jessica Lin",
    role: "Community Outreach Lead",
    email: "jessica.lin@room320.com",
    phone: "+1234567894",
    bio: "Fosters collaboration between environmental bodies and university campuses through interactive workshops.",
    photo: "https://images.unsplash.com/photo-1544005313-94ddf0286df2?auto=format&fit=crop&q=80&w=200",
    joinedDate: "2024-09-20",
    createdAt: "2024-09-20T16:00:00Z"
  }
];

export const defaultGallery: GalleryItem[] = [
  {
    id: 1,
    title: "Eco-Lab Setup",
    description: "Our primary testing lab inside Room 320 with air-monitoring configurations.",
    imagePath: "https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?auto=format&fit=crop&q=80&w=600",
    uploadedBy: "Admin",
    category: "Research",
    isApproved: true,
    createdAt: "2026-06-01T09:00:00Z"
  },
  {
    id: 2,
    title: "Indoor Vertical Forest",
    description: "A high-efficiency visual layout of indoor bio-walls for active oxygenation.",
    imagePath: "https://images.unsplash.com/photo-1530587191325-3db32d826c18?auto=format&fit=crop&q=80&w=600",
    uploadedBy: "Admin",
    category: "Initiatives",
    isApproved: true,
    createdAt: "2026-06-05T10:15:00Z"
  },
  {
    id: 3,
    title: "Sensor Deployment Site",
    description: "Tariq deploying the new IoT humidity multi-sensor grid on the northern balcony.",
    imagePath: "https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&q=80&w=600",
    uploadedBy: "Tariq Al-Mansoor",
    category: "Research",
    isApproved: true,
    createdAt: "2026-06-12T14:30:00Z"
  },
  {
    id: 4,
    title: "Student Awareness Seminar",
    description: "A packed room during the sustainable space design workshop hosted by Jessica.",
    imagePath: "https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?auto=format&fit=crop&q=80&w=600",
    uploadedBy: "Jessica Lin",
    category: "Events",
    isApproved: true,
    createdAt: "2026-06-20T11:00:00Z"
  },
  {
    id: 5,
    title: "Smart Irrigation System",
    description: "A custom drip irrigation module running automatically based on soil health sensors.",
    imagePath: "https://images.unsplash.com/photo-1518531933037-91b2f5f229cc?auto=format&fit=crop&q=80&w=600",
    uploadedBy: "Admin",
    category: "Initiatives",
    isApproved: true,
    createdAt: "2026-06-25T16:45:00Z"
  }
];

export const defaultActivities: Activity[] = [
  {
    id: 1,
    title: "Carbon Ingress Audits",
    description: "A rigorous diagnostic review measuring internal university spaces against international ecological benchmarks.",
    imagePath: "https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&q=80&w=600",
    activityDate: "2026-05-12",
    createdAt: "2026-05-12T09:00:00Z"
  },
  {
    id: 2,
    title: "Air Filtration Hackathon",
    description: "An intense multi-disciplinary coding and hardware hackathon to build self-cleaning ventilation concepts.",
    imagePath: "https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?auto=format&fit=crop&q=80&w=600",
    activityDate: "2026-06-04",
    createdAt: "2026-06-04T10:00:00Z"
  },
  {
    id: 3,
    title: "Community Tree Planting Drive",
    description: "Planting over 200 air-purifying saplings around the research annex building to establish microclimates.",
    imagePath: "https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?auto=format&fit=crop&q=80&w=600",
    activityDate: "2026-06-25",
    createdAt: "2026-06-25T14:30:00Z"
  }
];

export const defaultAnnouncements: Announcement[] = [
  {
    id: 1,
    title: "New Air Sensor API Release",
    content: "The Room No. 320 IoT sensor stream is now accessible in beta format for researchers. Please generate an API token inside your custom user dashboard or request access via the chief systems architect.",
    createdAt: "2026-07-01T09:00:00Z"
  },
  {
    id: 2,
    title: "Annual Eco Summit 2026 Rescheduled",
    content: "Please note that our upcoming Summit has been rescheduled to October 12, 2026, due to logistical enhancements. Registration is free and remains open for all verified university students.",
    createdAt: "2026-07-03T10:00:00Z"
  },
  {
    id: 3,
    title: "Volunteers Needed: Balcony Greens",
    content: "We are looking for enthusiastic hands to help maintain the balcony garden beds and automated solar-drip feeds. 2-hour shifts are available on Wednesdays and Fridays.",
    createdAt: "2026-07-05T14:00:00Z"
  }
];

export const defaultContactMessages: ContactMessage[] = [
  {
    id: 1,
    name: "Robert Foster",
    email: "robert.f@example.com",
    subject: "Collaboration Proposal",
    message: "Hello, I represent the GreenTech Initiative and we are highly interested in integrating our carbon tracker modules with the sensor arrays in Room 320. Can we schedule a virtual call?",
    createdAt: "2026-07-04T09:00:00Z",
    isRead: false
  },
  {
    id: 2,
    name: "Amara Patel",
    email: "amara.p@example.com",
    subject: "Student Internships",
    message: "Are there any available student internship positions for undergraduate developers focusing on environmental IoT dashboarding? Thank you!",
    createdAt: "2026-07-05T11:30:00Z",
    isRead: true
  }
];
