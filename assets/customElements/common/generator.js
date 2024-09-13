import { createRoot } from "react-dom/client";
import React from "react";

class ElementGenerator extends HTMLElement {
    constructor(element) {
        super();
        this.element = element;
        this.root = null;  // On utilisera pour stocker la racine React
    }

    connectedCallback() {
        this.root = createRoot(this);

        // Récupère les attributs au moment de la connexion
        const props = this.getPropsFromAttributes();
        this.renderReactComponent(props);
    }

    // Fonction pour extraire les attributs du CustomElement
    getPropsFromAttributes() {
        const attributes = {};
        // Parcourt tous les attributs de l'élément HTML
        for (let attr of this.attributes) {
            const attrName = attr.name.startsWith("data-")
                ? attr.name.substring(5) // Supprime le préfixe 'data-' pour dataset
                : attr.name;
            attributes[attrName] = attr.value;
        }
        return attributes;
    }

    // Rend le composant React en passant les props
    renderReactComponent(props) {
        this.root.render(React.cloneElement(this.element, props));
    }

    // Lorsqu'un attribut change, on met à jour les props dans React
    attributeChangedCallback(name, oldValue, newValue) {
        if (oldValue !== newValue) {
            const props = this.getPropsFromAttributes();
            this.renderReactComponent(props);
        }
    }

    // Indique quels attributs on veut observer
    static get observedAttributes() {
        return ["data-*", "other-attribute"]; // Ajouter ici les attributs à observer
    }

    disconnectedCallback() {
        // Nettoyage si nécessaire
        this.root.unmount();
    }
}

// Fonction pour définir les Custom Elements
export const defineCustomElements = (tag, element) => {
    customElements.define(tag, class extends ElementGenerator {
        constructor() {
            super(element);
        }
    });
};
