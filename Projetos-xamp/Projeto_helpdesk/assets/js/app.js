const page = document.body.dataset.page || "";
const toastElement = document.getElementById("appToast");
const toastBody = toastElement ? toastElement.querySelector(".toast-body") : null;
const bootstrapToast = toastElement ? bootstrap.Toast.getOrCreateInstance(toastElement, { delay: 3500 }) : null;

const showToast = (message, variant = "primary") => {
  if (!toastElement || !toastBody || !bootstrapToast) {
    return;
  }

  toastElement.classList.remove(
    "text-bg-primary",
    "text-bg-success",
    "text-bg-danger",
    "text-bg-warning",
    "text-bg-info"
  );

  toastElement.classList.add(`text-bg-${variant}`);
  toastBody.textContent = message;
  bootstrapToast.show();
};

const toggleButtonState = (button, isLoading) => {
  if (!button) {
    return;
  }
  const spinner = button.querySelector(".spinner-border");
  button.disabled = isLoading;
  if (spinner) {
    spinner.classList.toggle("d-none", !isLoading);
  }
};

const apiRequest = async (endpoint, options = {}) => {
  const response = await fetch(endpoint, options);
  if (!response.ok) {
    let errorMessage = "Não foi possível completar a operação.";
    try {
      const errorPayload = await response.json();
      if (errorPayload?.message) {
        errorMessage = errorPayload.message;
      }
    } catch (error) {
      // noop: keep default message
    }
    throw new Error(errorMessage);
  }
  const data = await response.json();
  return data?.data ?? data;
};

const escapeHtml = (value) => {
  const div = document.createElement("div");
  div.textContent = value ?? "";
  return div.innerHTML;
};

const sanitizePayload = (formData) => {
  return {
    titulo: formData.get("titulo")?.toString().trim() ?? "",
    categoria: formData.get("categoria")?.toString().trim() ?? "",
    descricao: formData.get("descricao")?.toString().trim() ?? "",
  };
};

const formatDateTime = (value) => {
  if (!value) {
    return "";
  }
  try {
    return new Intl.DateTimeFormat("pt-BR", {
      dateStyle: "short",
      timeStyle: "short",
    }).format(new Date(value));
  } catch (error) {
    return value;
  }
};

const initCreateTicket = () => {
  const form = document.getElementById("ticketCreateForm");
  if (!form) {
    return;
  }

  const submitButton = form.querySelector("button[type='submit']");

  form.addEventListener("submit", async (event) => {
    event.preventDefault();
    const payload = sanitizePayload(new FormData(form));

    if (!payload.titulo || !payload.categoria || !payload.descricao) {
      showToast("Preencha todas as informações do chamado.", "warning");
      return;
    }

    try {
      toggleButtonState(submitButton, true);
      await apiRequest("api/tickets.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify(payload),
      });
      form.reset();
      showToast("Chamado registrado com sucesso!", "success");
    } catch (error) {
      showToast(error.message, "danger");
    } finally {
      toggleButtonState(submitButton, false);
    }
  });
};

const ticketListElement = document.getElementById("ticketsList");
const ticketEmptyElement = document.getElementById("ticketsEmpty");
const ticketLoadingElement = document.getElementById("ticketsLoading");
const ticketModalElement = document.getElementById("ticketModal");
const ticketEditForm = document.getElementById("ticketEditForm");
const ticketModal = ticketModalElement ? bootstrap.Modal.getOrCreateInstance(ticketModalElement) : null;
let editingTicket = null;

const renderTickets = (tickets = []) => {
  if (!ticketListElement) {
    return;
  }

  ticketListElement.innerHTML = "";

  if (!tickets.length) {
    ticketEmptyElement?.classList.remove("d-none");
    return;
  }

  ticketEmptyElement?.classList.add("d-none");

  tickets.forEach((ticket) => {
    const ownerLabel = escapeHtml(ticket.ownerLabel ?? (ticket.isOwner ? "Você" : `Usuário #${ticket.ownerId}`));
    const createdAt = formatDateTime(ticket.createdAt);
    const updatedAt = formatDateTime(ticket.updatedAt);
    const title = escapeHtml(ticket.titulo);
    const category = escapeHtml(ticket.categoria);
    const description = escapeHtml(ticket.descricao).replace(/\n/g, "<br>");

    const card = document.createElement("div");
    card.className = "col-12 col-lg-6";
    card.innerHTML = `
      <div class="card h-100">
        <div class="card-body d-flex flex-column">
          <div class="d-flex justify-content-between align-items-start gap-3">
            <div>
              <h2 class="h5 mb-1">${title}</h2>
              <span class="badge badge-soft mb-3">${category}</span>
            </div>
            <div class="text-end small text-muted">
              <span>${ownerLabel}</span>
            </div>
          </div>
          <p class="text-muted flex-grow-1">${description}</p>
          <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mt-3 pt-3 border-top">
            <div class="small text-muted">
              <div>${createdAt ? `Criado em ${createdAt}` : ""}</div>
              <div>${updatedAt ? `Atualizado em ${updatedAt}` : ""}</div>
            </div>
            <div class="d-flex gap-2">
              ${ticket.canEdit ? `<button class="btn btn-sm btn-outline-primary" data-action="edit">Editar</button>` : ""}
              ${ticket.canDelete ? `<button class="btn btn-sm btn-outline-danger" data-action="delete">Excluir</button>` : ""}
            </div>
          </div>
        </div>
      </div>
    `;

    const editButton = card.querySelector("[data-action='edit']");
    const deleteButton = card.querySelector("[data-action='delete']");

    if (editButton) {
      editButton.addEventListener("click", () => {
        editingTicket = ticket;
        if (!ticketModalElement || !ticketEditForm) {
          return;
        }
        ticketEditForm.querySelector("[name='titulo']").value = ticket.titulo;
        ticketEditForm.querySelector("[name='categoria']").value = ticket.categoria;
        ticketEditForm.querySelector("[name='descricao']").value = ticket.descricao;
        ticketModal?.show();
      });
    }

    if (deleteButton) {
      deleteButton.addEventListener("click", async () => {
        if (!confirm("Deseja realmente excluir este chamado?")) {
          return;
        }
        try {
          await apiRequest(`api/tickets.php?id=${encodeURIComponent(ticket.id)}`, {
            method: "DELETE",
          });
          showToast("Chamado excluído com sucesso.", "success");
          await loadTickets();
        } catch (error) {
          showToast(error.message, "danger");
        }
      });
    }

    ticketListElement.appendChild(card);
  });
};

const loadTickets = async () => {
  if (ticketLoadingElement) {
    ticketLoadingElement.classList.remove("d-none");
  }
  try {
    const data = await apiRequest("api/tickets.php");
    renderTickets(Array.isArray(data) ? data : []);
  } catch (error) {
    showToast(error.message, "danger");
  } finally {
    if (ticketLoadingElement) {
      ticketLoadingElement.classList.add("d-none");
    }
  }
};

const initTicketModal = () => {
  if (!ticketEditForm) {
    return;
  }

  const submitButton = ticketEditForm.querySelector("button[type='submit']");

  ticketEditForm.addEventListener("submit", async (event) => {
    event.preventDefault();
    if (!editingTicket) {
      return;
    }

    const payload = sanitizePayload(new FormData(ticketEditForm));
    if (!payload.titulo || !payload.categoria || !payload.descricao) {
      showToast("Preencha todas as informações do chamado.", "warning");
      return;
    }

    try {
      toggleButtonState(submitButton, true);
      await apiRequest(`api/tickets.php?id=${encodeURIComponent(editingTicket.id)}`, {
        method: "PUT",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify(payload),
      });
      showToast("Chamado atualizado com sucesso.", "success");
      ticketModal?.hide();
      await loadTickets();
    } catch (error) {
      showToast(error.message, "danger");
    } finally {
      toggleButtonState(submitButton, false);
    }
  });

  ticketModalElement?.addEventListener("hidden.bs.modal", () => {
    editingTicket = null;
    ticketEditForm.reset();
  });
};

const flashMessage = document.body.dataset.flash;
if (flashMessage) {
  showToast(flashMessage, "info");
}

switch (page) {
  case "create-ticket":
    initCreateTicket();
    break;
  case "tickets":
    initTicketModal();
    loadTickets();
    break;
  default:
    break;
}
