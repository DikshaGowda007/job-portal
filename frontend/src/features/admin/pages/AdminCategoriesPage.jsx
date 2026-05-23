import { useState } from "react";
import { useQuery, useMutation, useQueryClient } from "@tanstack/react-query";
import { categoryApi } from "@/api/category.api";
import { formatDate } from "@/utils/formatters";
import Loader from "@/components/common/Loader";
import EmptyState from "@/components/common/EmptyState";
import { Plus, Pencil, Trash2, Check, X, Tag } from "lucide-react";

export default function AdminCategoriesPage() {
  const queryClient = useQueryClient();
  const [newName, setNewName] = useState("");
  const [showAddForm, setShowAddForm] = useState(false);
  const [editingId, setEditingId] = useState(null);
  const [editName, setEditName] = useState("");

  const { data, isLoading } = useQuery({
    queryKey: ["admin-categories"],
    queryFn: () => categoryApi.list({}).then((r) => r.data?.data?.list ?? r.data?.data ?? []),
  });

  const addMutation = useMutation({
    mutationFn: (name) => categoryApi.add({ name }),
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ["admin-categories"] });
      queryClient.invalidateQueries({ queryKey: ["categories"] });
      setNewName("");
      setShowAddForm(false);
    },
  });

  const editMutation = useMutation({
    mutationFn: ({ id, name }) => categoryApi.edit({ id, name }),
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ["admin-categories"] });
      queryClient.invalidateQueries({ queryKey: ["categories"] });
      setEditingId(null);
    },
  });

  const deleteMutation = useMutation({
    mutationFn: (id) => categoryApi.delete({ id }),
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ["admin-categories"] });
      queryClient.invalidateQueries({ queryKey: ["categories"] });
    },
  });

  const categories = Array.isArray(data) ? data : [];

  return (
    <div className="space-y-5">
      {/* Header */}
      <div className="flex items-center justify-between">
        <div>
          <h1 className="text-2xl font-bold text-gray-900 dark:text-white">Job Categories</h1>
          <p className="mt-0.5 text-sm text-gray-500 dark:text-gray-400">
            {categories.length > 0 ? `${categories.length} categories` : "Manage categories for job postings"}
          </p>
        </div>
        <button
          onClick={() => { setShowAddForm(true); setEditingId(null); }}
          className="flex items-center gap-2 rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-400"
        >
          <Plus size={15} /> Add Category
        </button>
      </div>

      {/* Add form */}
      {showAddForm && (
        <div className="flex items-center gap-3 rounded-2xl border border-indigo-200 bg-indigo-50/40 p-4 dark:border-indigo-900/40 dark:bg-indigo-950/20">
          <Tag size={16} className="shrink-0 text-indigo-500" />
          <input
            autoFocus
            type="text"
            value={newName}
            onChange={(e) => setNewName(e.target.value)}
            onKeyDown={(e) => {
              if (e.key === "Enter" && newName.trim()) addMutation.mutate(newName.trim());
              if (e.key === "Escape") { setShowAddForm(false); setNewName(""); }
            }}
            placeholder="Category name…"
            className="flex-1 rounded-xl border border-gray-300 bg-white px-3.5 py-2 text-sm outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 dark:border-gray-700 dark:bg-gray-900"
          />
          <button
            onClick={() => { if (newName.trim()) addMutation.mutate(newName.trim()); }}
            disabled={!newName.trim() || addMutation.isPending}
            className="flex items-center gap-1.5 rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700 disabled:opacity-60"
          >
            <Check size={14} /> {addMutation.isPending ? "Adding…" : "Add"}
          </button>
          <button
            onClick={() => { setShowAddForm(false); setNewName(""); }}
            className="rounded-xl border border-gray-300 px-3 py-2 text-sm hover:bg-gray-100 dark:border-gray-700 dark:hover:bg-gray-800"
          >
            <X size={14} />
          </button>
        </div>
      )}

      {/* List */}
      {isLoading ? (
        <Loader />
      ) : categories.length === 0 ? (
        <EmptyState title="No categories yet" description="Add your first category using the button above." />
      ) : (
        <div className="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900">
          <table className="w-full text-sm">
            <thead>
              <tr className="border-b border-gray-100 bg-gray-50 dark:border-gray-800 dark:bg-gray-900/60">
                <th className="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">#</th>
                <th className="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Category Name</th>
                <th className="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Created</th>
                <th className="px-5 py-3.5 text-right text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Actions</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-gray-100 dark:divide-gray-800">
              {categories.map((cat, i) => (
                <tr key={cat.id} className="group transition hover:bg-gray-50 dark:hover:bg-gray-800/40">
                  <td className="px-5 py-4 text-sm text-gray-400 dark:text-gray-500">{i + 1}</td>

                  <td className="px-5 py-4">
                    {editingId === cat.id ? (
                      <input
                        autoFocus
                        value={editName}
                        onChange={(e) => setEditName(e.target.value)}
                        onKeyDown={(e) => {
                          if (e.key === "Enter" && editName.trim()) editMutation.mutate({ id: cat.id, name: editName.trim() });
                          if (e.key === "Escape") setEditingId(null);
                        }}
                        className="w-full max-w-xs rounded-xl border border-indigo-400 bg-white px-3 py-1.5 text-sm outline-none focus:ring-2 focus:ring-indigo-200 dark:border-indigo-600 dark:bg-gray-800"
                      />
                    ) : (
                      <div className="flex items-center gap-2">
                        <span className="flex size-7 items-center justify-center rounded-lg bg-indigo-50 dark:bg-indigo-900/30">
                          <Tag size={13} className="text-indigo-500" />
                        </span>
                        <span className="font-medium text-gray-900 dark:text-white">{cat.name}</span>
                      </div>
                    )}
                  </td>

                  <td className="px-5 py-4 text-sm text-gray-500 dark:text-gray-400">{formatDate(cat.created_at)}</td>

                  <td className="px-5 py-4">
                    <div className="flex items-center justify-end gap-2">
                      {editingId === cat.id ? (
                        <>
                          <button
                            onClick={() => { if (editName.trim()) editMutation.mutate({ id: cat.id, name: editName.trim() }); }}
                            disabled={!editName.trim() || editMutation.isPending}
                            className="flex items-center gap-1 rounded-lg bg-indigo-600 px-3 py-1.5 text-xs font-medium text-white hover:bg-indigo-700 disabled:opacity-60"
                          >
                            <Check size={12} /> Save
                          </button>
                          <button
                            onClick={() => setEditingId(null)}
                            className="flex items-center gap-1 rounded-lg border border-gray-300 px-3 py-1.5 text-xs text-gray-600 hover:bg-gray-100 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-800"
                          >
                            <X size={12} /> Cancel
                          </button>
                        </>
                      ) : (
                        <>
                          <button
                            onClick={() => { setEditingId(cat.id); setEditName(cat.name); }}
                            className="flex items-center gap-1 rounded-lg border border-gray-200 px-3 py-1.5 text-xs text-gray-600 opacity-0 transition hover:bg-gray-100 group-hover:opacity-100 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-800"
                          >
                            <Pencil size={12} /> Edit
                          </button>
                          <button
                            onClick={() => {
                              if (confirm(`Delete "${cat.name}"?`)) deleteMutation.mutate(cat.id);
                            }}
                            disabled={deleteMutation.isPending}
                            className="flex items-center gap-1 rounded-lg border border-red-200 bg-red-50 px-3 py-1.5 text-xs text-red-600 opacity-0 transition hover:bg-red-100 group-hover:opacity-100 disabled:opacity-40 dark:border-red-900 dark:bg-red-950/30 dark:text-red-400"
                          >
                            <Trash2 size={12} /> Delete
                          </button>
                        </>
                      )}
                    </div>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      )}
    </div>
  );
}
