import { useQuery } from "@tanstack/react-query";
import { recruiterApi } from "@/api/recruiter.api";
import StatsCard from "@/components/cards/StatsCard";
import Loader from "@/components/common/Loader";
import {
  Briefcase,
  XCircle,
  Clock,
  Users,
  CheckCircle,
  Star,
  CalendarCheck,
  Handshake,
  UserCheck,
  XOctagon,
} from "lucide-react";

export default function RecruiterDashboard() {
  const { data, isLoading } = useQuery({
    queryKey: ["recruiter-dashboard"],
    queryFn: () => recruiterApi.dashboard().then((r) => r.data?.data),
  });

  if (isLoading) return <Loader />;

  const jobs = data?.jobs ?? {};
  const apps = data?.applications ?? {};

  return (
    <div>
      <div className="mb-6">
        <h1 className="text-3xl font-bold">Recruiter Dashboard</h1>
        <p className="mt-1 text-gray-500 dark:text-gray-400">
          Manage your job postings and applicants
        </p>
      </div>

      <h2 className="text-lg font-semibold mb-3 text-gray-700 dark:text-gray-300">
        Jobs
      </h2>
      <div className="grid grid-cols-1 gap-6 sm:grid-cols-4 mb-8">
        <StatsCard
          title="Total Jobs"
          value={jobs.total ?? "—"}
          icon={Briefcase}
          color="indigo"
        />
        <StatsCard
          title="Open"
          value={jobs.open ?? "—"}
          icon={CheckCircle}
          color="green"
        />
        <StatsCard
          title="Closed"
          value={jobs.closed ?? "—"}
          icon={XCircle}
          color="red"
        />
        <StatsCard
          title="Expired"
          value={jobs.expired ?? "—"}
          icon={Clock}
          color="yellow"
        />
      </div>

      <h2 className="text-lg font-semibold mb-3 text-gray-700 dark:text-gray-300">
        Applications
      </h2>
      <div className="grid grid-cols-1 gap-6 sm:grid-cols-4">
        <StatsCard
          title="Total"
          value={apps.total ?? "—"}
          icon={Users}
          color="blue"
        />
        <StatsCard
          title="Pending"
          value={apps.pending ?? "—"}
          icon={Clock}
          color="yellow"
        />
        <StatsCard
          title="Reviewed"
          value={apps.reviewed ?? "—"}
          icon={CheckCircle}
          color="indigo"
        />
        <StatsCard
          title="Shortlisted"
          value={apps.shortlisted ?? "—"}
          icon={Star}
          color="purple"
        />
        <StatsCard
          title="Interview"
          value={apps.interview ?? "—"}
          icon={CalendarCheck}
          color="blue"
        />
        <StatsCard
          title="Offered"
          value={apps.offered ?? "—"}
          icon={Handshake}
          color="green"
        />
        <StatsCard
          title="Hired"
          value={apps.hired ?? "—"}
          icon={UserCheck}
          color="green"
        />
        <StatsCard
          title="Rejected"
          value={apps.rejected ?? "—"}
          icon={XOctagon}
          color="red"
        />
      </div>
    </div>
  );
}
