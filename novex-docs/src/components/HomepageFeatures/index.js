import clsx from 'clsx';
import Heading from '@theme/Heading';
import styles from './styles.module.css';

const FeatureList = [
    {
        title: 'Arquitectura multi-inquilino',
        emoji: '🏢',
        emojiColor: '#ff6b35', // Novex Orange
        description: (
            <>
                Diseñado para escalar con un robusto sistema multiusuario. Cada cliente obtiene su
                propia base de datos aislada y recursos para máxima seguridad y rendimiento.
            </>
        ),
    },
    {
        title: 'Arquitectura limpia',
        emoji: '🏛️',
        emojiColor: '#ff4081', // Novex Pink
        description: (
            <>
                Basado en sólidos principios arquitectónicos, separando las capas de dominio,
                aplicación e infraestructura para lograr una base de código fácil de mantener y
                probar.
            </>
        ),
    },
    {
        title: 'Sistema ERP modular',
        emoji: '🧩',
        emojiColor: '#9c27b0', // Novex Purple
        description: (
            <>
                Diseño totalmente modular con módulos de Inventario, Ventas, CRM y RR. HH.
                Fácilmente extensible para añadir nuevas funcionalidades sin interrumpir la
                funcionalidad principal.
            </>
        ),
    },
];

function Feature({ emoji, emojiColor, title, description }) {
    return (
        <div className={clsx('col col--4')}>
            <div className="text--center">
                <span
                    role="img"
                    aria-label={title}
                    style={{
                        fontSize: '5rem',
                        display: 'block',
                        marginBottom: '1rem',
                        color: emojiColor,
                    }}
                >
                    {emoji}
                </span>
            </div>
            <div className="text--center padding-horiz--md">
                <Heading as="h3">{title}</Heading>
                <p>{description}</p>
            </div>
        </div>
    );
}

export default function HomepageFeatures() {
    return (
        <section className={styles.features}>
            <div className="container">
                <div className="row">
                    {FeatureList.map((props, idx) => (
                        <Feature key={idx} {...props} />
                    ))}
                </div>
            </div>
        </section>
    );
}
