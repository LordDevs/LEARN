(() => {
  'use strict';

  const DEFAULT_ENDPOINT = 'api/tickets.php';

  function getCsrfToken() {
    const meta = document.querySelector('meta[name="csrf-token"]');
    return meta ? meta.getAttribute('content') || '' : '';
  }

  function showFeedback(alertElement, message, type = 'success') {
    if (!alertElement) {
      return;
    }

    alertElement.classList.remove('d-none', 'alert-success', 'alert-danger', 'alert-warning');
    alertElement.classList.add('alert', `alert-${type}`);
    alertElement.textContent = message;
  }

  function hideFeedback(alertElement) {
    if (!alertElement) {
      return;
    }

    alertElement.classList.add('d-none');
    alertElement.textContent = '';
  }

  function renderTickets(container, tickets) {
    if (!container) {
      return;
    }

    container.innerHTML = '';

    if (!Array.isArray(tickets) || tickets.length === 0) {
      const emptyMessage = document.createElement('p');
      emptyMessage.className = 'text-muted mb-0';
      emptyMessage.textContent = 'Nenhum chamado cadastrado.';
      container.appendChild(emptyMessage);
      return;
    }

    const fragment = document.createDocumentFragment();

    tickets.forEach((ticket) => {
      const card = document.createElement('div');
      card.className = 'card mb-3 shadow-sm';

      const body = document.createElement('div');
      body.className = 'card-body';

      const title = document.createElement('h5');
      title.className = 'card-title';
      title.textContent = ticket.titulo || 'Chamado';

      const subtitle = document.createElement('h6');
      subtitle.className = 'card-subtitle mb-2 text-muted';
      const createdAt = ticket.criado_em ? new Date(ticket.criado_em) : null;
      const formattedDate = createdAt && !Number.isNaN(createdAt.valueOf())
        ? createdAt.toLocaleString('pt-BR')
        : null;
      if (formattedDate) {
        subtitle.textContent = `${ticket.categoria || 'Sem categoria'} · ${formattedDate}`;
      } else {
        subtitle.textContent = ticket.categoria || 'Sem categoria';
      }

      const description = document.createElement('p');
      description.className = 'card-text';
      description.textContent = ticket.descricao || '';

      body.append(title, subtitle, description);
      card.appendChild(body);
      fragment.appendChild(card);
    });

    container.appendChild(fragment);
  }

  function showLoading(container) {
    if (!container) {
      return;
    }

    container.innerHTML = '';
    const loading = document.createElement('p');
    loading.className = 'text-muted mb-0';
    loading.textContent = 'Carregando chamados...';
    container.appendChild(loading);
  }

  document.addEventListener('DOMContentLoaded', () => {
    const csrfToken = getCsrfToken();
    const form = document.querySelector('[data-ticket-form]');
    const listContainer = document.querySelector('[data-ticket-list]');
    const feedbackElement = document.querySelector('[data-ticket-feedback]');
    const refreshButtons = document.querySelectorAll('[data-ticket-refresh]');

    const endpoint = (form && form.dataset.ticketEndpoint)
      || (listContainer && listContainer.dataset.ticketEndpoint)
      || DEFAULT_ENDPOINT;

    let isLoadingTickets = false;

    async function loadTickets() {
      if (!listContainer || isLoadingTickets) {
        return;
      }

      isLoadingTickets = true;
      refreshButtons.forEach((button) => {
        button.disabled = true;
      });

      showLoading(listContainer);

      try {
        const response = await fetch(endpoint, {
          headers: {
            Accept: 'application/json',
          },
        });

        if (!response.ok) {
          throw new Error('Falha ao carregar os chamados.');
        }

        const data = await response.json();
        renderTickets(listContainer, data.tickets);
      } catch (error) {
        const alert = document.createElement('div');
        alert.className = 'alert alert-danger mb-0';
        alert.textContent = error instanceof Error ? error.message : 'Erro inesperado.';
        listContainer.innerHTML = '';
        listContainer.appendChild(alert);
      } finally {
        isLoadingTickets = false;
        refreshButtons.forEach((button) => {
          button.disabled = false;
        });
      }
    }

    if (listContainer) {
      loadTickets();
      document.addEventListener('tickets:reload', loadTickets);
      refreshButtons.forEach((button) => {
        button.addEventListener('click', () => {
          loadTickets();
        });
      });
    }

    if (form) {
      form.addEventListener('submit', async (event) => {
        hideFeedback(feedbackElement);

        if (!form.checkValidity()) {
          event.preventDefault();
          event.stopPropagation();
          form.classList.add('was-validated');
          return;
        }

        event.preventDefault();
        event.stopPropagation();

        const payload = {
          titulo: form.titulo.value.trim(),
          categoria: form.categoria.value,
          descricao: form.descricao.value.trim(),
          csrf_token: csrfToken,
        };

        try {
          const response = await fetch(form.dataset.ticketEndpoint || endpoint, {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              Accept: 'application/json',
              'X-CSRF-Token': csrfToken,
            },
            body: JSON.stringify(payload),
          });

          const data = await response.json().catch(() => ({}));

          if (!response.ok) {
            const message = data && data.errors
              ? Object.values(data.errors).join(' ')
              : (data && data.error) || 'Não foi possível registrar o chamado.';
            showFeedback(feedbackElement, message, 'danger');
            return;
          }

          form.reset();
          form.classList.remove('was-validated');
          showFeedback(feedbackElement, 'Chamado registrado com sucesso!', 'success');
          document.dispatchEvent(new CustomEvent('tickets:reload'));
        } catch (error) {
          showFeedback(feedbackElement, 'Falha na comunicação com o servidor. Tente novamente.', 'danger');
        }
      });
    }
  });
})();
